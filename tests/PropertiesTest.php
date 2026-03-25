<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Templex\Exceptions\VariableNotFoundException;
use Myerscode\Templex\Properties;

final class PropertiesTest extends TestCase
{
    public function testPropertiesCanFindVariable(): void
    {
        $properties = new Properties(['Users' => ['Fred', 'Tor', 'Chris']]);

        $this->assertEquals(['Fred', 'Tor', 'Chris'], $properties->resolveValue(['variable' => 'Users']));
    }

    public function testPropertiesThrowsExceptionIfNotFound(): void
    {
        $properties = new Properties(['Users' => ['Fred', 'Tor', 'Chris']]);
        $this->expectException(VariableNotFoundException::class);
        $properties->resolveValue(['variable' => 'name']);
    }

    public function testPropertiesReturnsVariables(): void
    {
        $data = ['name' => 'Fred', 'age' => 30];
        $properties = new Properties($data);
        $this->assertSame($data, $properties->variables());
    }
}
