<?php

namespace App\Domain\Site;

use App\Services\CloudflareService;

class SiteRepository
{
    /**
     * CloudflareService instance.
     *
     * @var CloudflareService
     */
    private CloudflareService $cloudflare_service;

    /**
     * SiteRepository constructor.
     *
     * @param CloudflareService $cloudflare_service
     */
    public function __construct(CloudflareService $cloudflare_service)
    {
        $this->cloudflare_service = $cloudflare_service;
    }
}