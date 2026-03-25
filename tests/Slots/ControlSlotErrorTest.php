<?php

declare(strict_types=1);

namespace Tests\Slots;

use Exception;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Slots\ControlSlot;
use ReflectionMethod;
use Tests\TestCase;

final class ControlSlotErrorTest extends TestCase
{
    private ControlSlot $controlSlot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controlSlot = new ControlSlot($this->stubManager);
    }

    public function testApplyForIncrementThrowsOnUnknownType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown increment type: **');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'applyForIncrement');
        $reflectionMethod->invoke($this->controlSlot, 1, '**', 2);
    }

    public function testEvaluateForConditionThrowsOnUnknownOperator(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown for loop operator: <>');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'evaluateForCondition');
        $reflectionMethod->invoke($this->controlSlot, 1, '<>', 2);
    }

    public function testParseForConditionThrowsOnInvalidCondition(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop condition: invalid');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'parseForCondition');
        $reflectionMethod->invoke($this->controlSlot, 'invalid');
    }

    public function testParseForIncrementThrowsOnInvalidIncrement(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop increment: invalid');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'parseForIncrement');
        $reflectionMethod->invoke($this->controlSlot, 'invalid');
    }

    public function testParseForInitThrowsOnInvalidInit(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop initialization: invalid');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'parseForInit');
        $reflectionMethod->invoke($this->controlSlot, 'invalid', new Properties([]));
    }

    public function testResolveControlThrowsOnUnknownStructure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown control structure: while');

        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'resolveControl');
        $reflectionMethod->invoke($this->controlSlot, 'while', '-1-0', '', new Properties([]));
    }

    public function testResolveForValueFallsBackToString(): void
    {
        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'resolveForValue');
        $result = $reflectionMethod->invoke($this->controlSlot, 'unknown', new Properties([]));

        $this->assertSame('unknown', $result);
    }

    public function testResolveForValueHandlesBooleanLiterals(): void
    {
        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'resolveForValue');

        $this->assertTrue($reflectionMethod->invoke($this->controlSlot, 'true', new Properties([])));
        $this->assertFalse($reflectionMethod->invoke($this->controlSlot, 'false', new Properties([])));
    }

    public function testResolveForValueHandlesStringLiterals(): void
    {
        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'resolveForValue');

        $this->assertSame('hello', $reflectionMethod->invoke($this->controlSlot, '"hello"', new Properties([])));
        $this->assertSame('world', $reflectionMethod->invoke($this->controlSlot, "'world'", new Properties([])));
    }

    public function testResolveSwitchValueFallsBackToRawString(): void
    {
        $reflectionMethod = new ReflectionMethod(ControlSlot::class, 'resolveSwitchValue');
        $result = $reflectionMethod->invoke($this->controlSlot, 'some_unknown_value', new Properties([]));

        $this->assertSame('some_unknown_value', $result);
    }
}
