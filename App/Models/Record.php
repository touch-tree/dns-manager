<?php

namespace App\Models;

class Record
{
    /**
     * Record ID.
     *
     * @var string
     */
    private string $id;

    /**
     * Record name.
     *
     * @var string
     */
    private string $name;

    /**
     * Record content.
     *
     * @var string
     */
    private string $content;

    /**
     * Get type.
     *
     * @var string
     */
    private string $type;

    /**
     * Get record ID.
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
    public function set_id(string $id): Record
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
    public function set_name(string $name): Record
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return $this
     */
    public function set_content(string $content): Record
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     * @return $this
     */
    public function set_type(string $type): Record
    {
        $this->type = $type;

        return $this;
    }
}