<?php

namespace App\Http\Controllers;

use App\Domain\Site\SiteRepository;
use App\Domain\Site\SiteService;
use App\Framework\Foundation\ParameterBag;
use App\Framework\Foundation\View;
use App\Framework\Http\RedirectResponse;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\UpdateRequest;
use App\Services\CloudflareService;
use Exception;
use function Sodium\add;

class DashboardController
{
    /**
     * SiteService instance.
     *
     * @var SiteService
     */
    private SiteService $site_service;

    /**
     * SiteRepository instance.
     *
     * @var SiteRepository
     */
    private SiteRepository $site_repository;

    /**
     * DashboardController constructor.
     *
     * @return void
     */
    public function __construct(SiteService $site_service, SiteRepository $site_repository)
    {
        $this->site_service = $site_service;
        $this->site_repository = $site_repository;
    }

    /**
     * Default view.
     *
     * @return View
     */
    public function index(): View
    {
        $sites = $this->site_repository->sites();

        return view('dashboard.index')
            ->with('domains', $sites->all())
            ->with('cloudflare_service', app(CloudflareService::class));
    }

    /**
     * Edit domain view.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $site = $this->site_repository->get($id);
        $pagerules = $this->site_service->get_pagerules($id);

        // pagerule not always set for each domain if pagerule not set then just don't display pagerule destination etc.

        return view('domain.edit')
            ->with('domain', $site)
            ->with('dns_root', $this->site_service->get_dns_record($id, $site->name()))
            ->with('dns_sub', $this->site_service->get_dns_record($id, 'www.' . $site->name()))
            ->with('pagerule_forwarding_url', $pagerules[0]['actions'][0]['value']['url'] ?? '');
    }

    /**
     * Update domain action.
     *
     * @param UpdateRequest $request
     * @param string $id
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        if ($request->validate()->errors()->any()) {
            return back();
        }

        $site = $this->site_repository->get($id);

        if (!$site) {
            return back()
                ->with('message_header', 'Unable to resolve site option')
                ->with('message_content', 'No zone found with given id')
                ->with('message_type', 'error');
        }

        $root_dns = $this->site_service->update_dns_record($site->id(),
            [
                'name' => $site->name(),
                'content' => $request->input('root_cname_target'),
            ]
        );

        if (!$root_dns) {
            add_error('update_root_dns_record', 'Unable to update root DNS record');
        }

        $sub_dns = $this->site_service->update_dns_record($site->id(),
            [
                'name' => 'www.' . $site->name(),
                'content' => $request->input('sub_cname_target'),
            ]
        );

        if (!$sub_dns) {
            add_error('update_sub_dns_record', 'Unable to update sub DNS record');
        }

        if ($this->site_service->update_pagerules_forwarding_url($site->id(), $request->input('pagerule_forwarding_url'))) {
            add_error('update_pagerules_forwarding_url', 'Unable to update forwarding URL for every pagerule.');
        }

        if (retrieve_error_bag()->any()) {
            return back()
                ->with('message_header', 'Problems with updating site')
                ->with('message_content', 'Failed update request.')
                ->with('message_type', 'error');
        }

        return back()
            ->with('message_header', 'Updated site')
            ->with('message_content', 'Site was updated successfully')
            ->with('message_type', 'success');
    }

    /**
     * Details domain view.
     *
     * @param string $id
     * @return View
     */
    public function details(string $id): View
    {
        $site = $this->site_repository->get($id);

        return view('domain.details')->with('domain', $site);
    }

    /**
     * Details domain modal.
     *
     * @param string $id
     * @return View
     */
    public function details_modal(string $id): View
    {
        $domain = $this->site_repository->get($id);

        return view(resource_path('views/templates/modal.php'),
            [
                'title' => 'Details for ' . $domain->name(),
                'content' => view(resource_path('views/domain/details.content.php'), ['domain' => $domain])->render()
            ]
        );
    }

    /**
     * Add domain view.
     *
     * @return View
     */
    public function add(): View
    {
        return view('domain.add');
    }

    /**
     * Create domain action.
     *
     * @param CreateRequest $request Form request.
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function create(CreateRequest $request): RedirectResponse
    {
        if ($request->validate()->errors()->any()) {
            return back();
        }

        $response = $this->site_service->add_site(
            [
                'name' => $request->input('domain'),
                'account_id' => config('api_client_id')
            ]
        );

        if (!empty($response['errors'])) {
            if (find_object_by_properties($response['errors'], ['code' => '1061'])) {
                return back()->with_errors(
                    [
                        'domain' => 'There is another site with the same domain name, unable to have duplicate sites under the same domain name.'
                    ]
                );
            }

            return back()
                ->with('message_header', 'Unable to add site')
                ->with('message_content', 'Unable to add site due to an internal server error.')
                ->with('message_type', 'error');
        }

        $site = $response['result'];

        $this->site_repository->save($site);

        if (!$this->site_service->set_ssl($site->id(), 'flexible')) {
            add_error('set_ssl', 'Unable to set SSL to flexible');
        }

        if (!$this->site_service->set_pseudo_ip($site->id(), 'overwrite_header')) {
            add_error('set_pseudo_ip', 'Unable to set pseudo IP to overwrite header');
        };

        if (!$this->site_service->set_https($site->id(), 'on')) {
            add_error('set_https', 'Unable to turn on HTTPS');
        }

        if (!$this->site_service->reset_dns_records($site->id())) {
            add_error('reset_dns_records', 'Encountered some issues resetting DNS records due to being unable to delete some DNS records');
        }

        $root_dns = $this->site_service->add_dns_record($site->id(),
            [
                'name' => '@',
                'content' => $request->input('root_cname_target'),
            ]
        );

        if (!$root_dns) {
            add_error('add_root_dns_record', 'Unable to add root DNS record');
        }

        $sub_dns = $this->site_service->add_dns_record($site->id(),
            [
                'name' => 'www',
                'content' => $request->input('sub_cname_target'),
            ]
        );

        if (!$sub_dns) {
            add_error('add_sub_dns_record', 'Unable to add sub DNS record');
        }

        if (!$this->site_service->reset_pagerules($site->id())) {
            add_error('reset_pagerules', 'Encountered some issues resetting pagerules due to being unable to delete some pagerules');
        }

        $pagerule = $this->site_service->add_pagerule($site->id(),
            [
                'url' => $request->input('pagerule_url'),
                'forwarding_url' => $request->input('pagerule_forwarding_url')
            ]
        );

        if (!$pagerule) {
            add_error('pagerule', 'Unable to add pagerule URL');
        }

        $pagerule_full = $this->site_service->add_pagerule($site->id(),
            [
                'url' => $request->input('pagerule_full_url'),
                'forwarding_url' => $request->input('pagerule_forwarding_url')
            ]
        );

        if (!$pagerule_full) {
            add_error('pagerule', 'Unable to add full pagerule URL');
        }

        if (retrieve_error_bag()->any()) {
            return back()
                ->with('message_header', 'Encountered issues with site setup')
                ->with('message_content', 'Site is added, but setup encountered some issues.')
                ->with('message_type', 'error');
        }

        return back()
            ->with('message_header', 'Added site')
            ->with('message_content', 'Site added and setup is done.')
            ->with('message_type', 'success');
    }

    /**
     * Verify nameservers domain action.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function verify_nameservers(string $id): RedirectResponse
    {
        $response = $this->site_service->verify_nameservers($id);

        if (!empty($response['errors'])) {
            if (find_object_by_properties($response['errors'], ['code' => '1224'])) {
                return back()
                    ->with('message_header', 'Unable to check nameservers')
                    ->with('message_content', 'This request cannot be made because it can only be called once an hour')
                    ->with('message_type', 'error');
            }

            return back()
                ->with('message_header', 'Checking nameservers failed')
                ->with('message_content', 'Failed to send check nameservers request')
                ->with('message_type', 'error');
        }

        return back()
            ->with('message_header', 'Started checking nameservers')
            ->with('message_content', 'Nameserver check started successfully')
            ->with('message_type', 'success');
    }
}
