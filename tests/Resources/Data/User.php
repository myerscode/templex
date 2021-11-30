<?php

namespace Tests\Resources\Data;

class User
{
    public function __construct(protected string $username, protected string $email)
    {
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
