<?php

namespace App\Repositories;

use App\Models\Record;
use App\Services\RecordService;
use Framework\Support\Cache;
use Framework\Support\Collection;

class RecordRepository
{
    /**
     * DNS records.
     *
     * @var Collection<Record>
     */
    private Collection $dns_records;

    /**
     * SiteService instance.
     *
     * @var RecordService
     */
    private RecordService $record_service;

    /**
     * RecordRepository constructor.
     *
     * @param RecordService $site_service
     */
    public function __construct(RecordService $site_service)
    {
        $this->record_service = $site_service;
    }

    /**
     * Get DNS records.
     *
     * @param string $id Zone ID.
     * @return Collection<Record>
     */
    public function all(string $id): Collection
    {
        $records = Cache::get('cache.dns_records');

        if (!$records) {
            $records = $this->record_service->get_dns_records($id);

            Cache::put('cache.dns_records', $records, 3600);
        }

        return $this->dns_records = $records;
    }

    /**
     * Save a DNS record.
     *
     * @param Record $record
     * @return RecordRepository
     */
    public function save(Record $record): RecordRepository
    {
        $this->dns_records->set($record->name(), $record);

        return $this;
    }

    /**
     * Get a DNS record.
     *
     * @param string $name DNS record name.
     * @return RecordRepository|null
     */
    public function get(string $name): ?RecordRepository
    {
        return $this->dns_records->get($name);
    }

    /**
     * Removes every single DNS record from this repository.
     *
     * @return RecordRepository
     */
    public function clear(): RecordRepository
    {
        $this->dns_records = new Collection();

        return $this;
    }
}