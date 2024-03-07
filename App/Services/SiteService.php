<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Plan;
use App\Models\Site;
use Framework\Support\Collection;

class SiteService
{
    /**
     * CloudflareService instance.
     *
     * @var CloudflareService
     */
    private CloudflareService $cloudflare_service;

    /**
     * SiteService constructor.
     *
     * @param CloudflareService $cloudflare_service
     */
    public function __construct(CloudflareService $cloudflare_service)
    {
        $this->cloudflare_service = $cloudflare_service;
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
        $site = $this->cloudflare_service->get_site($id);

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
        $zones = $this->cloudflare_service->get_sites();
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
     * Get DNS record.
     *
     * @param string $id Zone ID.
     * @param string $name DNS record name.
     * @return mixed|null
     */
    public function get_dns_record(string $id, string $name)
    {
        return $this->cloudflare_service->get_dns_record($id, $name);
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
        $response = $this->cloudflare_service->delete_dns_record($id, $dns_record_id);

        return empty($response['errors']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_dns_records(string $id): bool
    {
        $records = $this->cloudflare_service->get_dns_records($id);
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
        $response = $this->cloudflare_service->delete_pagerule($id, $pagerule_id);

        return empty($response['errors']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_pagerules(string $id): bool
    {
        $records = $this->cloudflare_service->get_pagerules($id);
        $status = true;

        foreach ($records['result'] as $pagerule) {
            $response = $this->delete_pagerule($id, $pagerule['id']);

            if (!$response) {
                $status = false;
            }
        }

        return $status;
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
     * Add DNS record.
     *
     * @param string $id Zone ID.
     * @param array{content: string, name: string} $data
     * @return bool
     */
    public function add_dns_record(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->add_dns_record($id,
            [
                'type' => 'CNAME',
                'name' => $data['name'],
                'content' => $data['content'],
                'proxied' => true,
                'ttl' => 1,
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Update DNS record.
     *
     * @param string $id Zone ID.
     * @param array{content: string, name: string} $data
     * @return bool
     */
    public function update_dns_record(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->update_dns_record($id, $data['name'],
            [
                'content' => $data['content'],
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Add pagerule.
     *
     * @param string $id Zone ID.
     * @param array{url: string, forwarding_url: string} $data
     * @return bool
     */
    public function add_pagerule(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->add_pagerule($id,
            [
                'status' => 'active',
                'targets' => [
                    [
                        'target' => 'url',
                        'constraint' => [
                            'operator' => 'matches',
                            'value' => $data['url'],
                        ],
                    ],
                ],
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $data['forwarding_url'],
                            'status_code' => 301,
                        ],
                    ],
                ],
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

    /**
     * Get pagerules.
     *
     * @param string $id Zone ID.
     * @return array|null
     */
    public function get_pagerules(string $id): ?array
    {
        $response = $this->cloudflare_service->get_pagerules($id);

        if (!empty($response['errors'])) {
            return null;
        }

        return $response['result'];
    }

    /**
     * Update a pagerule.
     *
     * @param string $id Zone ID.
     * @param string $pagerule_id
     * @param array{forwarding_url: string} $data
     * @return bool
     */
    public function update_pagerule(string $id, string $pagerule_id, array $data): bool
    {
        $response = $this->cloudflare_service->update_pagerule($id, $pagerule_id,
            [
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $data['forwarding_url'],
                            'status_code' => 301,
                        ],
                    ],
                ],
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Update pagerules.
     *
     * @param string $id Zone ID.
     * @poram string $value
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function update_pagerules_forwarding_url(string $id, string $value): bool
    {
        $pagerules = $this->get_pagerules($id);
        $status = true;

        foreach ($pagerules as $pagerule) {
            $response = $this->update_pagerule($id, $pagerule['id'],
                [
                    'forwarding_url' => $value,
                ]
            );

            if (!$response) {
                $status = false;
            }
        }

        return $status;
    }
}