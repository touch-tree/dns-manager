<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Plan;
use App\Models\Site;
use Framework\Foundation\ServiceProvider;
use Framework\Support\Collection;

class SiteService extends ServiceProvider
{
    /**
     * CloudflareService instance.
     *
     * @var CloudflareService
     */
    private CloudflareService $cloudflare_service;

    /**
     * RecordRepository instance.
     *
     * @var RecordService
     */
    private RecordService $record_service;

    /**
     * PageruleService
     *
     * @var PageruleService
     */
    private PageruleService $pagerule_service;

    /**
     * SiteService constructor.
     *
     * @param CloudflareService $cloudflare_service
     * @param RecordService $record_service
     * @param PageruleService $pagerule_service
     */
    public function __construct(CloudflareService $cloudflare_service, RecordService $record_service, PageruleService $pagerule_service)
    {
        $this->cloudflare_service = $cloudflare_service;
        $this->record_service = $record_service;
        $this->pagerule_service = $pagerule_service;
    }

    /**
     * Make an Account based on the zone data.
     *
     * @param array $data
     * @return Account|null Null whenever 'data' cannot be inferred into an Account class.
     */
    private function make_account(array $data): ?Account
    {
        $account = new Account();

        $account
            ->set_id($data['id'])
            ->set_name($data['name']);

        return $account;
    }

    /**
     * Make a Plan based on the zone data.
     *
     * @param array $data
     * @return Plan|null
     */
    private function make_plan(array $data): ?Plan
    {
        $plan = new Plan();

        $plan
            ->set_id($data['id'])
            ->set_name($data['name'])
            ->set_currency($data['currency'])
            ->set_frequency($data['frequency'])
            ->set_is_subscribed($data['is_subscribed'])
            ->set_can_subscribe($data['can_subscribe'])
            ->set_price($data['price']);

        return $plan;
    }

    /**
     * Make a Site based on the zone data.
     *
     * @param array $data
     * @return Site
     */
    private function make_site(array $data): Site
    {
        $site = new Site();

        $account = $this->make_account($data['account']);
        $plan = $this->make_plan($data['plan']);

        $records = $this->record_service->get_dns_records($data['id']);
        $pagerules = $this->pagerule_service->get_pagerules($data['id']);

        $site
            ->set_id($data['id'])
            ->set_name($data['name'])
            ->set_type($data['type'])
            ->set_paused($data['paused'])
            ->set_original_registrar($data['original_registrar'])
            ->set_original_dnshost($data['original_dnshost'])
            ->set_permissions($data['permissions'])
            ->set_activated_on($data['activated_on'])
            ->set_modified_on($data['modified_on'])
            ->set_created_on($data['created_on'])
            ->set_nameservers($data['name_servers'])
            ->set_original_nameservers($data['original_name_servers'])
            ->set_status($data['status'])
            ->set_account($account)
            ->set_plan($plan)
            ->set_dns_records($records)
            ->set_pagerules($pagerules);

        return $site;
    }

    /**
     * Add a new site.
     *
     * @param array{name: string, account_id: string} $data
     * @return array{result: Site|null, errors: array}
     */
    public function add_site(array $data): array
    {
        $site = null;

        $response = $this->cloudflare_service->add_site(
            [
                'name' => $data['name'],
                'type' => 'full',
                'jump_start' => true,
                'account' => [
                    'id' => $data['account_id']
                ],
                'plan' => [
                    'id' => 'free'
                ],
            ]
        );

        if (!empty($response['result'])) {
            $site = $this->make_site($response['result']);
        }

        return [
            'result' => $site,
            'errors' => $response['errors'],
        ];
    }

    /**
     * Get site by ID.
     *
     * @param string $id Zone ID.
     * @return Site|null Returns null whenever the site cannot be resolved nor found.
     */
    public function get_site(string $id): ?Site
    {
        $response = $this->cloudflare_service->get_site($id);

        if (empty($response = $response['result'])) {
            return null;
        }

        return $this->make_site($response);
    }

    /**
     * Get sites as a collection.
     *
     * @return Collection<Site>
     */
    public function get_sites(): Collection
    {
        $response = $this->cloudflare_service->get_sites();
        $sites = new Collection();

        foreach ($response['result'] as $zone) {
            $site = $this->make_site($zone);
            $sites->set($site->id(), $site);
        }

        return $sites;
    }

    /**
     * Set SSL.
     *
     * @param string $id Zone ID.
     * @param string $value
     * @return bool
     */
    public function set_ssl(string $id, string $value): bool
    {
        $response = $this->cloudflare_service->set_ssl($id,
            [
                'value' => $value
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Set pseudo IP.
     *
     * @param string $id Zone ID.
     * @param string $value
     * @return bool
     */
    public function set_pseudo_ip(string $id, string $value): bool
    {
        $response = $this->cloudflare_service->set_pseudo_ip($id,
            [
                'value' => $value
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Set HTTPS.
     *
     * @param string $id Zone ID.
     * @param string $value
     * @return bool
     */
    public function set_https(string $id, string $value): bool
    {
        $response = $this->cloudflare_service->set_https($id,
            [
                'value' => $value
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Verify nameservers.
     *
     * @param string $id Zone ID.
     * @return array{result: array, errors: array}
     */
    public function check_nameservers(string $id): array
    {
        $response = $this->cloudflare_service->check_nameservers($id);

        return [
            'result' => $response,
            'errors' => $response['errors'],
        ];
    }
}