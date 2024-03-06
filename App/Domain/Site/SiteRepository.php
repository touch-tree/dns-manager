<?php

namespace App\Domain\Site;

use App\Framework\Support\Collection;

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
    }

    /**
     * Get sites.
     *
     * @return Collection
     */
    public function sites(): Collection
    {
        $session = session();

        if (is_null($session->get('cache.sites'))) {
            $session->put('cache.sites', $this->site_service->get_sites());
        }

        return $this->sites = $session->get('cache.sites');
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