<?php

namespace Tests;

use Myerscode\Templex\Exceptions\VariableNotFoundException;
use Myerscode\Templex\Properties;

class PropertiesTest extends TestCase
{

    public function testPropertiesCanFindVariable(): void
    {
        $properties = new Properties(['Users' => ['Fred', 'Tor', 'Chris']]);

        $this->assertEquals(['Fred', 'Tor', 'Chris'],  $properties->resolveValue(['variable' => 'Users']));
    }

    public function testPropertiesThrowsExceptionIfNotFound(): void
    {
        $properties = new Properties(['Users' => ['Fred', 'Tor', 'Chris']]);
        $this->expectException(VariableNotFoundException::class);
        $properties->resolveValue(['variable' => 'name']);
    }
}
