<?php

namespace App\Domain\Site;

use App\Services\DashboardService;

class SiteRepository
{
    /**
     * DashboardService instance.
     *
     * @var DashboardService
     */
    private DashboardService $dashboard_service;

    /**
     * SiteRepository constructor.
     *
     * @param DashboardService $dashboard_service
     */
    public function __construct(DashboardService $dashboard_service)
    {
        $this->dashboard_service = $dashboard_service;
    }
}