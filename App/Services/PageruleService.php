<?php

namespace App\Services;

use App\Models\Pagerule;
use Framework\Http\Response;
use Framework\Support\Collection;

class PageruleService
{
    /**
     * CloudflareService instance.
     *
     * @var CloudflareService
     */
    private CloudflareService $cloudflare_service;

    /**
     * PageruleService constructor.
     *
     * @param CloudflareService $cloudflare_service
     */
    public function __construct(CloudflareService $cloudflare_service)
    {
        $this->cloudflare_service = $cloudflare_service;
    }

    /**
     * Make pagerule using pagerule data.
     *
     * @param array $data
     * @return Pagerule
     */
    public function make_pagerule(array $data): Pagerule
    {
        $pagerule = new Pagerule();

        $pagerule
            ->set_id($data['id'])
            ->set_url($data['targets'][0]['constraint']['value'])
            ->set_forwarding_url($data['actions'][0]['value']['url']);

        return $pagerule;
    }

    /**
     * Get pagerules.
     *
     * @param string $id Zone ID.
     * @return Collection<Pagerule>
     */
    public function get_pagerules(string $id): Collection
    {
        $response = $this->cloudflare_service->get_pagerules($id);
        $pagerules = new Collection();

        if (empty($response = $response['result'])) {
            return $pagerules;
        }

        foreach ($response as $pagerule) {
            $rule = $this->make_pagerule($pagerule);
            $pagerules->set($rule->url(), $rule);
        }

        return $pagerules;
    }

    /**
     * Get pagerule.
     *
     * @param string $id
     * @param string $pagerule_id
     * @return Pagerule|null
     */
    public function get_pagerule(string $id, string $pagerule_id): ?Pagerule
    {
        $response = $this->cloudflare_service->get_pagerule($id, $pagerule_id);

        if (empty($response = $response['result'])) {
            return null;
        }

        return $this->make_pagerule($response);
    }

    /**
     * Add pagerule.
     *
     * @param string $id Zone ID.
     * @param array{url: string, forwarding_url: string} $data
     * @return bool
     */
    public function add_pagerule(string $id, array $data): bool
    {
        $response = $this->cloudflare_service->add_pagerule($id,
            [
                'status' => 'active',
                'targets' => [
                    [
                        'target' => 'url',
                        'constraint' => [
                            'operator' => 'matches',
                            'value' => $data['url'],
                        ],
                    ],
                ],
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $data['forwarding_url'],
                            'status_code' => Response::HTTP_MOVED_PERMANENTLY,
                        ],
                    ],
                ],
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Update a pagerule.
     *
     * @param string $id Zone ID.
     * @param string $pagerule_id
     * @param array{forwarding_url: string} $data
     * @return bool
     */
    public function update_pagerule(string $id, string $pagerule_id, array $data): bool
    {
        $response = $this->cloudflare_service->update_pagerule($id, $pagerule_id,
            [
                'actions' => [
                    [
                        'id' => 'forwarding_url',
                        'value' => [
                            'url' => $data['forwarding_url'],
                            'status_code' => Response::HTTP_MOVED_PERMANENTLY,
                        ],
                    ],
                ],
            ]
        );

        return empty($response['errors']);
    }

    /**
     * Delete a single pagerule by ID.
     *
     * @param string $id
     * @param string $pagerule_id
     * @return bool
     */
    public function delete_pagerule(string $id, string $pagerule_id): bool
    {
        $response = $this->cloudflare_service->delete_pagerule($id, $pagerule_id);

        return empty($response['errors']);
    }

    /**
     * Delete every single DNS record by Zone ID.
     *
     * @param string $id Zone ID.
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function reset_pagerules(string $id): bool
    {
        $response = $this->cloudflare_service->get_pagerules($id);
        $status = true;

        foreach ($response['result'] as $pagerule) {
            if (!$this->delete_pagerule($id, $pagerule['id'])) {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * Update pagerules.
     *
     * @param string $id Zone ID.
     * @param array{forwarding_url: string} $data
     * @return bool Returns false whenever a single DNS record is unable to be deleted.
     */
    public function update_pagerules(string $id, array $data): bool
    {
        $status = true;

        foreach ($this->get_pagerules($id)->all() as $pagerule) {
            if (!$this->update_pagerule($id, $pagerule->id(), $data)) {
                $status = false;
            }
        }

        return $status;
    }
}