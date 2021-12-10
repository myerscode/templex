<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Stub\AbstractStub;
use Myerscode\Templex\Stub\StubInterface;

class Stub extends AbstractStub implements StubInterface
{
    public function __construct(protected string $name, protected string $path)
    {
        //
    }

    public function path(): string
    {
        return $this->path;
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function content(): string
    {
        if (file_exists($this->path)) {
            return (string) file_get_contents($this->path);
        }

        throw new TemplateNotFoundException("Template $this->name not found");
    }
}
