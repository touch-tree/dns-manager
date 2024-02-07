<?php

namespace App\Services;

use App\Core\Http;

class DashboardService
{
    /**
     * Http instance
     *
     * @var Http
     */
    private Http $http;

    /**
     * CloudflareAPIService constructor
     *
     * @return void
     */
    public function __construct(Http $http)
    {
        $this->http = $http::set_headers(
            [
                'Content-Type: application/json',
                'Authorization: Bearer ' . config('api_token'),
            ]
        );
    }

    /**
     * Get all zones
     *
     * @return array
     */
    public function get_zones(): array
    {
        return $this->http->get(config('api_url') . '/zones')->json();
    }

    /**
     * Get zone by ID
     *
     * @param string $id Zone id
     * @return array
     */
    public function get_zone(string $id): array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id)->json();
    }

    /**
     * Add a new site
     *
     * @param array $options
     * @return array
     */
    public function add_site(array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/', $options)->json();
    }

    /**
     * Set SSL settings for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function set_ssl(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/ssl', $options)->json();
    }

    /**
     * Set Https settings for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function set_https(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/always_use_https', $options)->json();
    }

    /**
     * Set pseudo IPv4 settings for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function set_pseudo_ip(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/pseudo_ipv4', $options)->json();
    }

    /**
     * Get DNS records for a zone
     *
     * @param string $id Zone id
     * @return array
     */
    public function get_dns_records(string $id): array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id . '/dns_records')->json();
    }

    /**
     * Delete DNS record for a zone
     *
     * @param string $id Zone id
     * @param string $dns_record_id
     * @return array
     */
    public function delete_dns_record(string $id, string $dns_record_id): array
    {
        return $this->http->delete(config('api_url') . '/zones/' . $id . '/dns_records/' . $dns_record_id)->json();
    }

    /**
     * Add DNS record for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function add_dns_record(string $id, array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/' . $id . '/dns_records', $options)->json();
    }

    /**
     * Update page rule for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function add_pagerule(string $id, array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/' . $id . '/pagerules', $options)->json();
    }

    /**
     * Get all pagerules for a zone
     *
     * @param string $id Zone id
     * @return array
     */
    public function get_pagerules(string $id): array
    {
        return $this->http->get(config('api_url') . '/zones/' . $id . '/pagerules')->json();
    }

    /**
     * Delete a pagerule for a zone
     *
     * @param string $id Zone id
     * @param string $pagerule_id Pagerule id
     * @return array
     */
    public function delete_pagerule(string $id, string $pagerule_id): array
    {
        return $this->http->delete(config('api_url') . '/zones/' . $id . '/pagerules/' . $pagerule_id)->json();
    }

    /**
     * Verify nameservers for a zone
     *
     * @param string $id Zone id
     * @return array
     */
    public function verify_nameservers(string $id): array
    {
        return $this->http->put(config('api_url') . '/zones/' . $id . '/activation_check')->json();
    }
}