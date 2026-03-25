<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Stub;

final class StubTest extends TestCase
{
    public function testStubStoresProperties(): void
    {
        $path = __DIR__ . '/Resources/Templates/loop.stub';
        $name = 'loop';

        $stub = new Stub($name, $path);

        $this->assertSame($path, $stub->path());
        $this->assertSame($name, $stub->name());
    }

    public function testCanGetContent(): void
    {
        $path = __DIR__ . '/Resources/Templates/text-only.stub';
        $name = 'loop';

        $stub = new Stub($name, $path);

        $this->assertEquals(file_get_contents($path), $stub->content());
    }

    public function testThrowsExceptionIfStubDoesNotExist(): void
    {
        $path = __DIR__ . '/Resources/Templates/not-a-stub.stub';
        $name = 'not-a-stub';

        $stub = new Stub($name, $path);
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage('Template not-a-stub not found');

        $stub->content();
    }
}
