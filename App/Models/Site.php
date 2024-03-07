<?php

namespace App\Models;

class Site
{
    /**
     * Site ID.
     *
     * @var string
     */
    private string $id;

    /**
     * Site name.
     *
     * @var string
     */
    private string $name;

    /**
     * Site type.
     *
     * @var string
     */
    private string $type;

    /**
     * Original registrar.
     *
     * @var string|null
     */
    private ?string $original_registrar;

    /**
     * Original DNS host.
     *
     * @var string|null
     */
    private ?string $original_dnshost;

    /**
     * Site status.
     *
     * @var string
     */
    private string $status;

    /**
     * Whether site is paused or not.
     *
     * @var string
     */
    private string $paused;

    /**
     * The nameservers related to the site.
     *
     * @var array
     */
    private array $nameservers;

    /**
     * Original nameservers.
     *
     * @var array
     */
    private array $original_nameservers;

    /**
     * Modified on.
     *
     * @var string|null
     */
    private ?string $modified_on;

    /**
     * Created on.
     *
     * @var string
     */
    private string $created_on;

    /**
     * Activated on.
     *
     * @var string|null
     */
    private ?string $activated_on;

    /**
     * The account that manages the site.
     *
     * @var Account|null
     */
    private ?Account $account;

    /**
     * Site permissions.
     *
     * @var array
     */
    private array $permissions;

    /**
     * Payment plan for the site.
     *
     * @var Plan|null
     */
    private ?Plan $plan;

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
    public function set_id(string $id): Site
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get site name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Set site name.
     *
     * @param string $name
     * @return $this
     */
    public function set_name(string $name): Site
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get site type.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Set site type.
     *
     * @param string $type
     * @return $this
     */
    public function set_type(string $type): Site
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get site status.
     *
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * Set site status.
     *
     * @param string $status
     * @return $this
     */
    public function set_status(string $status): Site
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Check whether the site is paused or not.
     *
     * @return string
     */
    public function is_paused(): string
    {
        return $this->paused;
    }

    /**
     * Set paused.
     *
     * @param string $paused
     * @return $this
     */
    public function set_paused(string $paused): Site
    {
        $this->paused = $paused;

        return $this;
    }

    /**
     * Get the nameservers related to the site.
     *
     * @return array
     */
    public function nameservers(): array
    {
        return $this->nameservers;
    }

    /**
     * Set nameservers.
     *
     * @param array $nameservers
     * @return $this
     */
    public function set_nameservers(array $nameservers): Site
    {
        $this->nameservers = $nameservers;

        return $this;
    }

    /**
     * Get original nameservers.
     *
     * @return array
     */
    public function original_nameservers(): array
    {
        return $this->original_nameservers;
    }

    /**
     * Set original nameservers.
     *
     * @param array|null $original_nameservers
     * @return $this
     */
    public function set_original_nameservers(?array $original_nameservers): Site
    {
        $this->original_nameservers = $original_nameservers ?? [];

        return $this;
    }

    /**
     * Get modified on.
     *
     * @return string|null
     */
    public function modified_on(): ?string
    {
        return $this->modified_on;
    }

    /**
     * Set modified on.
     *
     * @param string|null $modified_on
     * @return $this
     */
    public function set_modified_on(?string $modified_on): Site
    {
        $this->modified_on = $modified_on;

        return $this;
    }

    /**
     * Get created on.
     *
     * @return string
     */
    public function created_on(): string
    {
        return $this->created_on;
    }

    /**
     * Set created on.
     *
     * @param string $created_on
     * @return $this
     */
    public function set_created_on(string $created_on): Site
    {
        $this->created_on = $created_on;

        return $this;
    }

    /**
     * Get activated on.
     *
     * @return string|null
     */
    public function activated_on(): ?string
    {
        return $this->activated_on;
    }

    /**
     * Set activated on.
     *
     * @param string|null $activated_on
     * @return $this
     */
    public function set_activated_on(?string $activated_on): Site
    {
        $this->activated_on = $activated_on;

        return $this;
    }

    /**
     * Get account related to the site.
     *
     * @return Account|null
     */
    public function account(): ?Account
    {
        return $this->account;
    }

    /**
     * Set account.
     *
     * @param Account $account
     * @return $this
     */
    public function set_account(Account $account): Site
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get permissions.
     *
     * @return array
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    /**
     * Set permissions.
     *
     * @param array $permissions
     * @return $this
     */
    public function set_permissions(array $permissions): Site
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Get payment plan.
     *
     * @return Plan|null
     */
    public function plan(): ?Plan
    {
        return $this->plan;
    }

    /**
     * Set payment plan.
     *
     * @param Plan $plan
     * @return $this
     */
    public function set_plan(Plan $plan): Site
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get original DNS host.
     *
     * @return string|null
     */
    public function original_dnshost(): ?string
    {
        return $this->original_dnshost;
    }

    /**
     * Set original DNS host.
     *
     * @param string|null $original_dnshost
     * @return $this
     */
    public function set_original_dnshost(?string $original_dnshost): Site
    {
        $this->original_dnshost = $original_dnshost;

        return $this;
    }

    /**
     * Get original registrar.
     *
     * @return string|null
     */
    public function original_registrar(): ?string
    {
        return $this->original_registrar;
    }

    /**
     * Set original registrar.
     *
     * @param string|null $original_registrar
     * @return $this
     */
    public function set_original_registrar(?string $original_registrar): Site
    {
        $this->original_registrar = $original_registrar;

        return $this;
    }
}
