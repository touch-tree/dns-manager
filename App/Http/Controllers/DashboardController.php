<?php

namespace App\Http\Controllers;

use App\Framework\Base\View;
use App\Framework\Http\Redirect;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\UpdateRequest;
use App\Services\DashboardService;
use Exception;

class DashboardController
{
    /**
     * DashboardService instance
     *
     * @var DashboardService
     */
    private DashboardService $dashboard_service;

    /**
     * DashboardController constructor
     *
     * @return void
     */
    public function __construct(DashboardService $dashboard_service)
    {
        $this->dashboard_service = $dashboard_service;
    }

    /**
     * Default view
     *
     * @return View
     */
    public function index(): View
    {
        $response = $this->dashboard_service->get_zones();

        return view('dashboard.index')
            ->with('domains', $response['result'])
            ->with('dashboard_service', app(DashboardService::class));
    }

    /**
     * Edit domain view
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $zone = $this->dashboard_service->get_zone($id);
        $pagerules = $this->dashboard_service->get_pagerules($id);

        // pagerule not always set for each domain fix this
        // if pagerule not set then just don't display pagerule destination etc.

        return view('domain.edit')
            ->with('domain', $zone['result'])
            ->with('dns_root', $this->dashboard_service->get_dns_record($id, $zone['result']['name']))
            ->with('dns_sub', $this->dashboard_service->get_dns_record($id, 'www.' . $zone['result']['name']))
            ->with('pagerule_destination_url', $pagerules['result'][0]['actions'][0]['value']['url'] ?? '');
    }

    /**
     * Update domain action
     *
     * @param UpdateRequest $request
     * @param string $id
     * @return Redirect
     *
     * @throws Exception
     */
    public function update(UpdateRequest $request, string $id): Redirect
    {
        if ($request->validate()->errors()) {
            return back();
        }

        $zone = $this->dashboard_service->get_zone($id);

        if (!$zone['success']) {
            return back()
                ->with('message_header', 'Unable to resolve site option')
                ->with('message_content', 'No zone found with given id')
                ->with('message_type', 'error');
        }

        // instead of using warning array, use error session and report all errors if not fetched manually to glob in view
        // errors()->any() etc.

        $warnings = [];

        // update dns records

        $dns_root = $this->dashboard_service->update_dns_record($id, $zone['result']['name'],
            [
                'content' => $request->input('root_cname_target'),
            ]
        );

        if (!$dns_root['success']) {
            $warnings[] = 'Unable to update CNAME ROOT';
        }

        $sub_root = $this->dashboard_service->update_dns_record($id, 'www.' . $zone['result']['name'],
            [
                'content' => $request->input('sub_cname_target'),
            ]
        );

        if (!$sub_root['success']) {
            $warnings[] = 'Unable to update CNAME SUB';
        }

        // update pagerules

        $pagerules = $this->dashboard_service->get_pagerules($id);

        foreach ($pagerules['result'] as $pagerule) {
            $pagerule_response = $this->dashboard_service->update_pagerule($id, $pagerule['id'],
                [
                    'actions' => [
                        [
                            'id' => 'forwarding_url',
                            'value' => [
                                'url' => $request->input('pagerule_destination_url'),
                                'status_code' => 301,
                            ],
                        ],
                    ],
                ]
            );

            if (!$pagerule_response['success']) {
                $warnings[] = 'Unable to update pagerule record with id: ' . $pagerule['id'];
            }
        }

        if (count($warnings)) {
            return back()
                ->with('message_header', 'Problems with updating site')
                ->with('message_content', 'Failed update requests: ' . join(', ', $warnings))
                ->with('message_type', 'error');
        }

        return back()
            ->with('message_header', 'Updated site')
            ->with('message_content', 'Site was updated successfully')
            ->with('message_type', 'success');
    }

    /**
     * Details domain view
     *
     * @param string $id
     * @return View
     */
    public function details(string $id): View
    {
        $domain = $this->dashboard_service->get_zone($id);

        return view('domain.details')->with('domain', $domain['result']);
    }

    /**
     * Details domain modal
     *
     * @param string $id
     * @return View
     */
    public function details_modal(string $id): View
    {
        $domain = $this->dashboard_service->get_zone($id);

        return view(resource_path('views/partials/modal_content.php'),
            [
                'title' => 'Details for ' . $domain['result']['name'],
                'content' => view(resource_path('views/domain/details_content.php'), ['domain' => $domain['result']])->render()
            ]
        );
    }

    /**
     * Add domain view
     *
     * @return View
     */
    public function add(): View
    {
        return view('domain.add');
    }

    /**
     * Create domain action
     *
     * @param CreateRequest $request Form request
     * @return Redirect
     *
     * @throws Exception
     */
    public function create(CreateRequest $request): Redirect
    {
        if ($request->validate()->errors()) {
            return back();
        }

        // check whether the pagerule targets are valid urls

        $page_rules = [
            $request->input('pagerule_url'),
            $request->input('pagerule_full_url'),
        ];

        $page_destination = $request->input('pagerule_destination_url');

        foreach ($page_rules as $rule) {
            $parsed_url = parse_url($page_destination);

            if (!isset($parsed_url['host'])) {
                return back()->with_errors(
                    [
                        'pagerule_destination_url' => 'Forwarding URL should be a proper URL'
                    ]
                );
            }

            $host = $parsed_url['host'] . $parsed_url['path'];

            if ($host === $rule || $host === 'www.' . $rule) {
                return back()->with_errors(
                    [
                        'pagerule_destination_url' => 'Forwarding URL matches the target and would cause a redirect loop'
                    ]
                );
            }
        }

        // create new site
        // we should really wrap these things in a normal object for our cloudflare interface. such as sites etc.

        $site = $this->dashboard_service->add_site(
            [
                'name' => $request->input('domain'),
                'jump_start' => true,
                'type' => 'full',
                'account' => [
                    'id' => config('api_client_id')
                ],
                'plan' => [
                    'id' => 'free'
                ]
            ]
        );

        if (!$site['success']) {
            if (search_object_by_properties($site['errors'], ['code' => '1061'])) {
                return back()->with_errors(
                    [
                        'domain' => 'There is another site with the same domain name, unable to have duplicate sites under the same domain name.'
                    ]
                );
            }

            return back()
                ->with('message_header', 'Unable to add site')
                ->with('message_content', 'Unable to add site due to an error with the Cloudflare API.')
                ->with('message_type', 'error');
        }

        $id = $site['result']['id'];
        $warnings = [];

        // settings for site setup
        // we should just not handle each error for set ssl, if this method fails then just generalize the throw. we can debug later lol

        $this->dashboard_service->set_ssl($id, ['value' => 'flexible']);

        $this->dashboard_service->set_pseudo_ip($id, ['value' => 'overwrite_header',]);

        $this->dashboard_service->set_https($id, ['value' => 'on']);

        // remove scanned dns records to prevent conflicts when we're adding new ones in
        // loop should be better and minimized

        $dns_records = $this->dashboard_service->get_dns_records($id);

        foreach ($dns_records['result'] as $dns_record) {
            $dns_response = $this->dashboard_service->delete_dns_record($id, $dns_record['id']);

            if (!$dns_response['success']) {
                $warnings[] = 'Unable to delete DNS record with id: ' . $dns_record['id'];
            }
        }

        // preparing to set up the dns records

        $dns_root = $this->dashboard_service->add_dns_record($id,
            [
                'type' => 'CNAME',
                'name' => '@',
                'content' => $request->input('root_cname_target'),
                'proxied' => true,
                'ttl' => 1,
            ]
        );

        if (!$dns_root['success']) {
            $warnings[] = 'Unable to add CNAME ROOT';
        }

        $dns_sub = $this->dashboard_service->add_dns_record($id,
            [
                'type' => 'CNAME',
                'name' => 'www',
                'content' => $request->input('sub_cname_target'),
                'proxied' => true,
                'ttl' => 1,
            ]
        );

        if (!$dns_sub['success']) {
            $warnings[] = 'Unable to add CNAME SUB';
        }

        // remove auto-added pagerules

        $pagerules = $this->dashboard_service->get_pagerules($id);

        foreach ($pagerules['result'] as $pagerule) {
            $pagerule_response = $this->dashboard_service->delete_pagerule($id, $pagerule['id']);

            if (!$pagerule_response['success']) {
                $warnings[] = 'Unable to delete pagerule record with id: ' . $pagerule['id'];
            }
        }

        // pagerule setup

        $pagerule_url = $this->dashboard_service->add_pagerule($id,
            [
                'status' => 'active',
                'targets' => [
                    [
                        'target' => 'url',
                        'constraint' => [
                            'operator' => 'matches',
                            'value' => $request->input('pagerule_url'),
                        ],
                    ],
                ],
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $request->input('pagerule_destination_url'),
                            'status_code' => 301,
                        ],
                    ],
                ],
            ]
        );

        if (!$pagerule_url['success']) {
            $warnings[] = 'Unable to set value for PAGERULE URL';
        }

        $pagerule_full_url = $this->dashboard_service->add_pagerule($id,
            [
                'status' => 'active',
                'targets' => [
                    [
                        'target' => 'url',
                        'constraint' => [
                            'operator' => 'matches',
                            'value' => $request->input('pagerule_full_url'),
                        ],
                    ],
                ],
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $request->input('pagerule_destination_url'),
                            'status_code' => 301,
                        ],
                    ],
                ],
            ]
        );

        if (!$pagerule_full_url['success']) {
            $warnings[] = 'Unable to set value for PAGERULE FULL URL';
        }

        if (count($warnings)) {
            return back()
                ->with('message_header', 'Encountered issues with site setup')
                ->with('message_content', 'Site is added, but setup encountered some issues: ' . join(', ', $warnings))
                ->with('message_type', 'error');
        }

        return back()
            ->with('message_header', 'Added site')
            ->with('message_content', 'Site added and setup is done')
            ->with('message_type', 'success');
    }

    /**
     * Verify nameservers domain action
     *
     * @param string $id
     * @return Redirect
     */
    public function verify_nameservers(string $id): Redirect
    {
        $response = $this->dashboard_service->verify_nameservers($id);

        if (!$response['success']) {
            if (search_object_by_properties($response['errors'], ['code' => '1224'])) {
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
