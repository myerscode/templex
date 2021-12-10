<?php

namespace Myerscode\Templex\Stub;

abstract class AbstractStub
{

    protected string $name;

    public function name(): string
    {
        return $this->name;
    }

}
