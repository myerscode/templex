<?php

namespace Tests\Slots;

use Exception;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Slots\ControlSlot;
use ReflectionMethod;
use Tests\TestCase;

class ControlSlotErrorTest extends TestCase
{
    private ControlSlot $slot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->slot = new ControlSlot($this->stubManager);
    }

    public function testResolveControlThrowsOnUnknownStructure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown control structure: while');

        $method = new ReflectionMethod(ControlSlot::class, 'resolveControl');
        $method->invoke($this->slot, 'while', '-1-0', '', new Properties([]));
    }

    public function testParseForInitThrowsOnInvalidInit(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop initialization: invalid');

        $method = new ReflectionMethod(ControlSlot::class, 'parseForInit');
        $method->invoke($this->slot, 'invalid', new Properties([]));
    }

    public function testParseForConditionThrowsOnInvalidCondition(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop condition: invalid');

        $method = new ReflectionMethod(ControlSlot::class, 'parseForCondition');
        $method->invoke($this->slot, 'invalid');
    }

    public function testParseForIncrementThrowsOnInvalidIncrement(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid for loop increment: invalid');

        $method = new ReflectionMethod(ControlSlot::class, 'parseForIncrement');
        $method->invoke($this->slot, 'invalid');
    }

    public function testEvaluateForConditionThrowsOnUnknownOperator(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown for loop operator: <>');

        $method = new ReflectionMethod(ControlSlot::class, 'evaluateForCondition');
        $method->invoke($this->slot, 1, '<>', 2);
    }

    public function testApplyForIncrementThrowsOnUnknownType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown increment type: **');

        $method = new ReflectionMethod(ControlSlot::class, 'applyForIncrement');
        $method->invoke($this->slot, 1, '**', 2);
    }

    public function testResolveSwitchValueFallsBackToRawString(): void
    {
        $method = new ReflectionMethod(ControlSlot::class, 'resolveSwitchValue');
        $result = $method->invoke($this->slot, 'some_unknown_value', new Properties([]));

        $this->assertSame('some_unknown_value', $result);
    }

    public function testResolveForValueHandlesStringLiterals(): void
    {
        $method = new ReflectionMethod(ControlSlot::class, 'resolveForValue');

        $this->assertSame('hello', $method->invoke($this->slot, '"hello"', new Properties([])));
        $this->assertSame('world', $method->invoke($this->slot, "'world'", new Properties([])));
    }

    public function testResolveForValueHandlesBooleanLiterals(): void
    {
        $method = new ReflectionMethod(ControlSlot::class, 'resolveForValue');

        $this->assertTrue($method->invoke($this->slot, 'true', new Properties([])));
        $this->assertFalse($method->invoke($this->slot, 'false', new Properties([])));
    }

    public function testResolveForValueFallsBackToString(): void
    {
        $method = new ReflectionMethod(ControlSlot::class, 'resolveForValue');
        $result = $method->invoke($this->slot, 'unknown', new Properties([]));

        $this->assertSame('unknown', $result);
    }
}
