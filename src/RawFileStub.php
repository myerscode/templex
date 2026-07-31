<?php

declare(strict_types=1);

namespace Myerscode\Templex;

use Myerscode\Templex\Stub\StubInterface;

readonly class RawFileStub implements StubInterface
{
    public function __construct(
        protected string $name,
        protected string $rawContent = '',
    ) {
    }

    public function content(): string
    {
        return $this->rawContent;
    }

    public function name(): string
    {
        return $this->name;
    }
}
