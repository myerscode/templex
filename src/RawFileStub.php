<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Stub\StubInterface;

class RawFileStub implements StubInterface
{
    public function __construct(protected string $name, protected string $rawContent = '')
    {
        //
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->rawContent;
    }
}
