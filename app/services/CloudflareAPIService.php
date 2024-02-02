<?php

namespace App\Services;

use App\Core\Http;
use ReflectionException;

class CloudflareAPIService
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
     * @throws ReflectionException
     */
    public function __construct()
    {
        // Set HTTP headers for API requests
        $this->http = app(Http::class)::set_headers(
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
    public function get_zone_by_id(string $id): array
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
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/ssl', $options);
    }

    /**
     * Set HTTPS settings for a zone
     *
     * @param string $id Zone id
     * @param array $options
     * @return array
     */
    public function set_https(string $id, array $options): array
    {
        return $this->http->patch(config('api_url') . '/zones/' . $id . '/settings/always_use_https', $options);
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
        return $this->http->get('/zones/' . $id . '/dns_records')->json();
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
        return $this->http->delete('/zones/' . $id . '/dns_records/' . $dns_record_id)->json();
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
    public function update_pagerule(string $id, array $options): array
    {
        return $this->http->post(config('api_url') . '/zones/' . $id . '/pagerules', $options)->json();
    }

    /**
     * Check activation status for a zone
     *
     * @param string $id Zone id
     * @return array
     */
    public function activation_check(string $id): array
    {
        return $this->http->put(config('api_url') . '/zones/' . $id . '/activation_check')->json();
    }
}