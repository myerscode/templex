<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Templex\RawFileStub;

final class RawFileStubTest extends TestCase
{
    public function testRawFileStubStoresProperties(): void
    {
        $content = 'Hello World';
        $name = 'raw-file';

        $rawFileStub = new RawFileStub($name, $content);

        $this->assertSame($name, $rawFileStub->name());
        $this->assertSame($content, $rawFileStub->content());
    }
}
