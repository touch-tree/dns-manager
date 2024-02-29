<?php

namespace App\Domain\Site;

use App\Framework\Base\ParameterBag;
use App\Framework\Support\Collection;
use App\Services\CloudflareService;

class SiteService
{
    /**
     * CloudflareService instance
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
     * Get sites.
     *
     * @return Collection<Site>
     */
    public function get_sites(): Collection
    {
        $response = $this->cloudflare_service->get_sites();
        $sites = new Collection();

        foreach ($response['result'] as $zone) {
            $site = new Site();

            $site
                ->set_id($zone['id'])
                ->set_name($zone['name'])
                ->set_account($zone['account'])
                ->set_activated_on($zone['activated_on'])
                ->set_created_on($zone['created_on']);
//                ->set_nameservers()
//                ->set_original_nameservers()
//                ->set_owner();

            $sites->set($site->get_id(), $site);
        }

        return $sites;
    }
}