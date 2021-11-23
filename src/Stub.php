<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;

class Stub
{
    public function __construct(protected string $name, protected string $path)
    {
        //
    }

    public function name(): string
    {
        return $this->name;
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
