<?php

namespace App\Services;

use Framework\Foundation\Http;
use Framework\Foundation\ServiceProvider;
use Framework\Http\HeaderBag;

class CloudflareService extends ServiceProvider
{
    /**
     * Http instance.
     *
     * @var Http
     */
    private Http $http;

    /**
     * CloudflareService constructor.
     *
     * @return void
     */
    public function __construct(Http $http)
    {
        $header = new HeaderBag();

        $header = $header
            ->set('Content-Type', 'application/json')
            ->set('Authorization', 'Bearer ' . config('api_token'));

        $this->http = $http::set_headers($header);
    }

    /**
     * Get DNS record by Zone ID and name.
     *
     * @param string $id Zone ID.
     * @param string $name
     * @return mixed|null
     */
    public function get_dns_record(string $id, string $name)
    {
        $dns_records = $this->get_dns_records($id);

        if (empty($dns_records = $dns_records['result'])) {
            return null;
        }

        foreach ($dns_records as $record) {
            if ($record['name'] === $name) {
                return $record;
            }
        }

        return null;
    }

    /**
     * Update DNS record for a zone by ID.
     *
     * @param string $id Zone ID.
     * @param string $name Record name.
     * @param array $options
     * @return array
     */
    public function update_dns_record(string $id, string $name, array $options): array
    {
        $dns_record = $this->get_dns_record($id, $name);

        if (!$dns_record) {
            return [
                'result' => [],
                'errors' => ['Unable to get DNS record to update.'],
            ];
        }

        return $this->http->patch(config('api_url') . '/zones/' . $id . '/dns_records/' . $dns_record['id'], $options)->json();
    }

    /**
     * Get all zones.
     *
     * @return array
     */
    public function get_sites(): array
    {
        return $this->http->get(config('api_url') . '/zones')->json();
    }

    /**
     * Get zone by ID.
     *
     * @param string $id Zone ID.
     * @return array
     */
    public function get_site(string $id): array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id)->json();
    }

    /**
     * Add a new site.
     *
     * @param array $options
     * @return array
     */
    public function add_site(array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/', $options)->json();
    }

    /**
     * Set SSL settings for a zone.
     *
     * @param string $id Zone ID.
     * @param array $options
     * @return array
     */
    public function set_ssl(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/ssl', $options)->json();
    }

    /**
     * Set Https settings for a zone.
     *
     * @param string $id Zone ID.
     * @param array $options
     * @return array
     */
    public function set_https(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/always_use_https', $options)->json();
    }

    /**
     * Set pseudo IPv4 settings for a zone.
     *
     * @param string $id Zone ID.
     * @param array $options
     * @return array
     */
    public function set_pseudo_ip(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/pseudo_ipv4', $options)->json();
    }

    /**
     * Get DNS records for a zone.
     *
     * @param string $id Zone ID.
     * @return array
     */
    public function get_dns_records(string $id): array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id . '/dns_records')->json();
    }

    /**
     * Delete DNS record for a zone.
     *
     * @param string $id Zone ID.
     * @param string $dns_record_id
     * @return array
     */
    public function delete_dns_record(string $id, string $dns_record_id): array
    {
        return $this->http->delete(config('api_url') . '/zones/' . $id . '/dns_records/' . $dns_record_id)->json();
    }

    /**
     * Add DNS record for a zone.
     *
     * @param string $id Zone ID.
     * @param array $options
     * @return array
     */
    public function add_dns_record(string $id, array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/' . $id . '/dns_records', $options)->json();
    }

    /**
     * Update page rule for a zone.
     *
     * @param string $id Zone ID.
     * @param array $options
     * @return array
     */
    public function add_pagerule(string $id, array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/' . $id . '/pagerules', $options)->json();
    }

    /**
     * Update page rule for a zone.
     *
     * @param string $id Zone ID.
     * @param string $pagerule_id
     * @return array|null
     */
    public function get_pagerule(string $id, string $pagerule_id): ?array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id . '/pagerules/' . $pagerule_id)->json();
    }

    /**
     * Get all pagerules for a zone.
     *
     * @param string $id Zone ID.
     * @return array|null
     */
    public function get_pagerules(string $id): ?array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id . '/pagerules')->json();
    }

    /**
     * Update a specific pagerule for a zone.
     *
     * @param string $id Zone ID.
     * @param string $pagerule_id Pagerule id.
     * @param array $options
     * @return array
     */
    public function update_pagerule(string $id, string $pagerule_id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/pagerules/' . $pagerule_id, $options)->json();
    }

    /**
     * Delete a pagerule for a zone.
     *
     * @param string $id Zone ID.
     * @param string $pagerule_id Pagerule id.
     * @return array
     */
    public function delete_pagerule(string $id, string $pagerule_id): array
    {
        return $this->http->delete(config('api_url') . '/zones/' . $id . '/pagerules/' . $pagerule_id)->json();
    }

    /**
     * Check nameservers for a zone.
     *
     * @param string $id Zone ID.
     * @return array
     */
    public function check_nameservers(string $id): array
    {
        return $this->http->put(config('api_url') . '/zones/' . $id . '/activation_check')->json();
    }
}