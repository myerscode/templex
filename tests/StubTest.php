<?php

namespace Tests;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Exceptions\VariableNotFoundException;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Stub;

class StubTest extends TestCase
{

    public function testStubStoresProperties(): void
    {
        $path = __DIR__ . '/Resources/Templates/loop.stub';
        $name = 'loop';

        $properties = new Stub($name, $path);

        $this->assertEquals($path, $properties->path());
        $this->assertEquals($name, $properties->name());
    }

    public function testCanGetContent(): void
    {
        $path = __DIR__ . '/Resources/Templates/text-only.stub';
        $name = 'loop';

        $properties = new Stub($name, $path);

        $this->assertEquals(file_get_contents($path), $properties->content());
    }

    public function testThrowsExceptionIfStubDoesNotExist(): void
    {
        $path = __DIR__ . '/Resources/Templates/not-a-stub.stub';
        $name = 'not-a-stub';

        $properties = new Stub($name, $path);
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage("Template not-a-stub not found");

        $properties->content();
    }
}
