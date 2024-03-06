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

    /**
     * Get plan ID.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Set plan ID.
     *
     * @param string $id
     * @return $this
     */
    public function set_id(string $id): Plan
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return $this
     */
    public function set_name(string $name): Plan
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get plan price.
     *
     * @return string
     */
    public function price(): string
    {
        return $this->price;
    }

    /**
     * Set plan price.
     *
     * @param string $price
     * @return $this
     */
    public function set_price(string $price): Plan
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get pricing currency.
     *
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * Set pricing currency.
     *
     * @param string $currency
     * @return $this
     */
    public function set_currency(string $currency): Plan
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the frequency of payments for a subscription.
     *
     * @return string
     */
    public function frequency(): string
    {
        return $this->frequency;
    }

    /**
     * Set the frequency of payments for a subscription.
     *
     * @param string $frequency
     * @return $this
     */
    public function set_frequency(string $frequency): Plan
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Is subscribed.
     *
     * @return string
     */
    public function is_subscribed(): string
    {
        return $this->is_subscribed;
    }

    /**
     * Is subscribed.
     *
     * @param string $is_subscribed
     * @return $this
     */
    public function set_is_subscribed(string $is_subscribed): Plan
    {
        $this->is_subscribed = $is_subscribed;

        return $this;
    }

    /**
     * Can subscribe.
     *
     * @return string
     */
    public function can_subscribe(): string
    {
        return $this->can_subscribe;
    }

    /**
     * Can subscribe.
     *
     * @param string $can_subscribe
     * @return $this
     */
    public function set_can_subscribe(string $can_subscribe): Plan
    {
        $this->can_subscribe = $can_subscribe;

        return $this;
    }
}