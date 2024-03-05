<?php

namespace App\Domain\Plan;

class Plan
{
    /**
     * Plan ID.
     *
     * @var string
     */
    private string $id;

    /**
     * Plan name.
     *
     * @var string
     */
    private string $name;

    /**
     * Plan price.
     *
     * @var string
     */
    private string $price;

    /**
     * Plan currency.
     *
     * @var string
     */
    private string $currency;

    /**
     * Plan frequency.
     *
     * @var string
     */
    private string $frequency;

    /**
     * Is subscribed.
     *
     * @var string
     */
    private string $is_subscribed;

    /**
     * Can subscribe.
     *
     * @var string
     */
    private string $can_subscribe;

    public function id(): string
    {
        return $this->id;
    }

    public function set_id(string $id): Plan
    {
        $this->id = $id;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function set_name(string $name): Plan
    {
        $this->name = $name;

        return $this;
    }

    public function price(): string
    {
        return $this->price;
    }

    public function set_price(string $price): Plan
    {
        $this->price = $price;

        return $this;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function set_currency(string $currency): Plan
    {
        $this->currency = $currency;

        return $this;
    }

    public function frequency(): string
    {
        return $this->frequency;
    }

    public function set_frequency(string $frequency): Plan
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function is_subscribed(): string
    {
        return $this->is_subscribed;
    }

    public function set_is_subscribed(string $is_subscribed): Plan
    {
        $this->is_subscribed = $is_subscribed;

        return $this;
    }

    public function can_subscribe(): string
    {
        return $this->can_subscribe;
    }

    public function set_can_subscribe(string $can_subscribe): Plan
    {
        $this->can_subscribe = $can_subscribe;

        return $this;
    }
}