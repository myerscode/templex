<?php

declare(strict_types=1);

namespace Myerscode\Templex\Stub;

interface StubInterface
{
    public function content(): string;

    public function name(): string;
}
