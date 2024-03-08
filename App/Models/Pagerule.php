<?php

namespace App\Models;

class Pagerule
{
    /**
     * Pagerule ID.
     *
     * @var string
     */
    private string $id;

    /**
     * Pagerule URL.
     *
     * @var string
     */
    private string $url;

    /**
     * Pagerule forwarding URL.
     *
     * @var string
     */
    private string $forwarding_url;

    /**
     * Get ID.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Set ID.
     *
     * @param string $id
     * @return $this
     */
    public function set_id(string $id): Pagerule
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get URL.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Set URL.
     *
     * @param string $url
     * @return $this
     */
    public function set_url(string $url): Pagerule
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get forwarding URL.
     *
     * @return string
     */
    public function forwarding_url(): string
    {
        return $this->forwarding_url;
    }

    /**
     * Set forwarding URL.
     *
     * @return $this
     */
    public function set_forwarding_url(string $forwarding_url): Pagerule
    {
        $this->forwarding_url = $forwarding_url;

        return $this;
    }
}