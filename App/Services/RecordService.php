<?php

namespace App\Services;

use App\Models\Record;
use Framework\Component\Service;
use Framework\Support\Collection;

class RecordService extends Service
{
    /**
     * CloudflareService instance.
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
     * Make DNS record using DNS record data.
     *
     * @param array $data
     * @return Record
     */
    private function make_dns_record(array $data): Record
    {
        $record = new Record();

        $record
            ->set_id($data['id'])
            ->set_name($data['name'])
            ->set_type($data['type'])
            ->set_content($data['content']);

        return $record;
    }

    /**
     * Get DNS records.
     *
     * @param string $id Zone ID.
     * @return Collection<Record>
     */
    public function get_dns_records(string $id): Collection
    {
        $response = $this->cloudflare_service->get_dns_records($id);
        $records = new Collection();

        if (empty($response = $response['result'])) {
            return $records;
        }

        foreach ($response as $dns_record) {
            $record = $this->make_dns_record($dns_record);
            $records->set($record->name(), $record);
        }

        return $records;
    }

    /**
     * Get DNS record.
     *
     * @param string $id Zone ID.
     * @param string $name DNS record name.
     * @return Record|null
     */
    public function get_dns_record(string $id, string $name): ?Record
    {
        $response = $this->cloudflare_service->get_dns_record($id, $name);

        if (empty($response)) {
            return null;
        }

        return $this->make_dns_record($response);
    }

    /**
     * Delete a single DNS record by ID.
     *
     * @param string $id
     * @param string $dns_record_id
     * @return bool
     */
    public function delete_dns_record(string $id, string $dns_record_id): bool
    {
        $response = $this->cloudflare_service->delete_dns_record($id, $dns_record_id);

        return empty($response['errors']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_dns_records(string $id): bool
    {
        $response = $this->cloudflare_service->get_dns_records($id);
        $status = true;

        foreach ($response['result'] as $record) {
            if (!$this->delete_dns_record($id, $record['id'])) {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * Add DNS record.
     *
     * @param string $id Zone ID.
     * @param array{content: string, name: string} $data
     * @return bool
     */
    public function add_dns_record(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->add_dns_record($id,
            [
                'type' => 'CNAME',
                'name' => $data['name'],
                'content' => $data['content'],
                'proxied' => true,
                'ttl' => 1,
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Update DNS record.
     *
     * @param string $id Zone ID.
     * @param array{content: string, name: string} $data
     * @return bool
     */
    public function update_dns_record(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->update_dns_record($id, $data['name'],
            [
                'content' => $data['content'],
            ]
        );

        return empty($response['errors']);
    }
}