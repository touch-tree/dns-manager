<?php

namespace App\Domain\Site;

use App\Domain\Account\Account;
use App\Domain\Plan\Plan;
use App\Framework\Foundation\ParameterBag;
use App\Framework\Support\Collection;
use App\Services\DashboardService;
use Exception;

class SiteService
{
    /**
     * CloudflareService instance.
     *
     * @var DashboardService
     */
    private DashboardService $dashboard_service;

    /**
     * SiteService constructor.
     *
     * @param DashboardService $dashboard_service
     */
    public function __construct(DashboardService $dashboard_service)
    {
        $this->dashboard_service = $dashboard_service;
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
            ->set_plan($plan);

        return $site;
    }

    /**
     * Add a new site.
     *
     * @param array{name: string, account_id: string} $data
     * @return array{response: Site|null, errors: array}
     */
    public function add_site(array $data): array
    {
        $site = $this->dashboard_service->add_site(
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

        return [
            'response' => $site['errors'] ? null : $this->make_site($site['result']),
            'errors' => $site['errors'],
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
        $site = $this->dashboard_service->get_site($id);

        if (empty($site = $site['result'])) {
            return null;
        }

        return $this->make_site($site);
    }

    /**
     * Get sites as a collection.
     *
     * @return Collection<Site>
     */
    public function get_sites(): Collection
    {
        $zones = $this->dashboard_service->get_sites();
        $sites = new Collection();

        foreach ($zones['result'] as $zone) {
            $site = $this->get_site($zone['id']);

            if (is_null($site)) {
                continue;
            }

            $sites->set($site->id(), $site);
        }

        return $sites;
    }

    /**
     * Delete a single DNS record by ID.
     *
     * @param string $id
     * @param string $dns_record_id
     * @return bool
     */
    public function delete_dns_record(string $id, string $dns_record_id): bool
    {
        $response = $this->dashboard_service->delete_dns_record($id, $dns_record_id);

        return empty($response['error']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_dns_records(string $id): bool
    {
        $records = $this->dashboard_service->get_dns_records($id);
        $status = true;

        foreach ($records['result'] as $record) {
            $response = $this->delete_dns_record($id, $record['id']);

            if (!$response) {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * Delete a single pagerule by ID.
     *
     * @param string $id
     * @param string $pagerule_id
     * @return bool
     */
    public function delete_pagerule(string $id, string $pagerule_id): bool
    {
        $response = $this->dashboard_service->delete_pagerule($id, $pagerule_id);

        return empty($response['error']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_pagerules(string $id): bool
    {
        $records = $this->dashboard_service->get_pagerules($id);
        $status = true;

        foreach ($records['result'] as $pagerule) {
            $response = $this->delete_pagerule($id, $pagerule['id']);

            if (!$response) {
                $status = false;
            }
        }

        return $status;
    }
}