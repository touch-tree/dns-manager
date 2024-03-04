<?php

namespace App\Domain\Site;

use App\Services\DashboardService;

class SiteRepository
{
    /**
     * CloudflareService instance.
     *
     * @var DashboardService
     */
    private DashboardService $cloudflare_service;

    /**
     * SiteRepository constructor.
     *
     * @param DashboardService $cloudflare_service
     */
    public function __construct(DashboardService $cloudflare_service)
    {
        $this->cloudflare_service = $cloudflare_service;
    }
}