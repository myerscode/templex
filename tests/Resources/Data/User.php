<?php

namespace Tests\Resources\Data;

class User
{
    public function __construct(protected string $username, protected string $email)
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
