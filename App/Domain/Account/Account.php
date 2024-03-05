<?php

namespace App\Domain\Account;

class Account
{
    /**
     * Account id.
     *
     * @var string
     */
    private string $id;

    /**
     * Account name.
     *
     * @var string
     */
    private string $name;

    /**
     * Get account ID.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Set account ID.
     *
     * @param string $id
     * @return $this
     */
    public function set_id(string $id): Account
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
    public function set_name(string $name): Account
    {
        $this->name = $name;

        return $this;
    }
}