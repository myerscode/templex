<?php

namespace Tests;

use Myerscode\Templex\RawFileStub;

class RawFileStubTest extends TestCase
{

    public function testRawFileStubStoresProperties(): void
    {
        $content = 'Hello World';
        $name = 'raw-file';

        $properties = new RawFileStub($name, $content);

        $this->assertEquals($name, $properties->name());
        $this->assertEquals($content, $properties->content());
    }
}
