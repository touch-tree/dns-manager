<?php

namespace App\Domain\Site;

class Site
{
    private $id;
    private $name;
    private $status;
    private $paused;
    private $name_servers;
    private $original_name_servers;
    private $modified_on;
    private $created_on;
    private $activated_on;
    private $owner;
    private $account;
    private $permissions;
    private $plan;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id): Site
    {
        $this->id = $id;

        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name): Site
    {
        $this->name = $name;

        return $this;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($status): Site
    {
        $this->status = $status;

        return $this;
    }

    public function get_paused()
    {
        return $this->paused;
    }

    public function set_paused($paused): Site
    {
        $this->paused = $paused;

        return $this;
    }

    public function get_name_servers()
    {
        return $this->name_servers;
    }

    public function set_name_servers($name_servers): Site
    {
        $this->name_servers = $name_servers;

        return $this;
    }

    public function get_original_name_servers()
    {
        return $this->original_name_servers;
    }

    public function set_original_name_servers($original_name_servers): Site
    {
        $this->original_name_servers = $original_name_servers;

        return $this;
    }

    public function get_modified_on()
    {
        return $this->modified_on;
    }

    public function set_modified_on($modified_on): Site
    {
        $this->modified_on = $modified_on;

        return $this;
    }

    public function get_created_on()
    {
        return $this->created_on;
    }

    public function set_created_on($created_on): Site
    {
        $this->created_on = $created_on;

        return $this;
    }

    public function get_activated_on()
    {
        return $this->activated_on;
    }

    public function set_activated_on($activated_on): Site
    {
        $this->activated_on = $activated_on;

        return $this;
    }

    public function get_owner()
    {
        return $this->owner;
    }

    public function set_owner($owner): Site
    {
        $this->owner = $owner;

        return $this;
    }

    public function get_account()
    {
        return $this->account;
    }

    public function set_account($account): Site
    {
        $this->account = $account;

        return $this;
    }

    public function get_permissions()
    {
        return $this->permissions;
    }

    public function set_permissions($permissions): Site
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function get_plan()
    {
        return $this->plan;
    }

    public function set_plan($plan): Site
    {
        $this->plan = $plan;

        return $this;
    }
}
