<?php

namespace App\Repositories;

use App\Models\Site;
use App\Services\SiteService;
use Framework\Support\Cache;
use Framework\Support\Collection;

class SiteRepository
{
    /**
     * Sites.
     *
     * @var Collection<Site>
     */
    private Collection $sites;

    /**
     * SiteService instance.
     *
     * @var SiteService
     */
    private SiteService $site_service;

    /**
     * SiteRepository constructor.
     *
     * @param SiteService $site_service
     */
    public function __construct(SiteService $site_service)
    {
        $this->site_service = $site_service;

        $this->all();
    }

    /**
     * Get sites.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $sites = Cache::get('cache.sites');

        if (!$sites) {
            $sites = $this->site_service->get_sites();

            Cache::put('cache.sites', $sites, 3600);
        }

        return $this->sites = $sites;
    }

    /**
     * Save a site.
     *
     * @param Site $site
     * @return SiteRepository
     */
    public function save(Site $site): SiteRepository
    {
        $this->sites->set($site->id(), $site);

        return $this;
    }

    /**
     * Get a site.
     *
     * @param string $id Site ID.
     * @return Site|null
     */
    public function get(string $id): ?Site
    {
        return $this->sites->get($id);
    }

    /**
     * Removes every single site from this repository.
     *
     * @return $this
     */
    public function clear(): SiteRepository
    {
        $this->sites = new Collection();

        return $this;
    }
}