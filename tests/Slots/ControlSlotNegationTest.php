<?php

declare(strict_types=1);

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

final class ControlSlotNegationTest extends TestCase
{
    public function testNegationInElseif(): void
    {
        $raw = '
        <{ if( $admin ) }>
            Admin
        <{ elseif( !$banned ) }>
            User
        <{ else }>
            Banned
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['admin' => false, 'banned' => false]));
        $this->assertStringContainsString('User', $result);
        $this->assertStringNotContainsString('Admin', $result);
        $this->assertStringNotContainsString('Banned', $result);
    }

    public function testNegationInsideLoop(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ if( !$item ) }>
                EMPTY
            <{ else }>
                <{ $item }>
            <{ endif }>
        <{ endforeach }>
        ';

        $data = ['items' => ['hello', false, 'world']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('hello', $result);
        $this->assertStringContainsString('EMPTY', $result);
        $this->assertStringContainsString('world', $result);
    }

    public function testNegationOfBooleanLiteral(): void
    {
        $raw = '
        <{ if( !false ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('pass', $result);
    }

    public function testNegationOfFalsyVariable(): void
    {
        $raw = '
        <{ if( !$active ) }>
            inactive
        <{ else }>
            active
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => false]));
        $this->assertStringContainsString('inactive', $result);
    }

    public function testNegationOfTrueLiteral(): void
    {
        $raw = '
        <{ if( !true ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('fail', $result);
    }
    public function testNegationOfTruthyVariable(): void
    {
        $raw = '
        <{ if( !$active ) }>
            inactive
        <{ else }>
            active
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => true]));
        $this->assertStringContainsString('active', $result);
        $this->assertStringNotContainsString('inactive', $result);
    }

    public function testNegationWithLogicalAnd(): void
    {
        $raw = '
        <{ if( !$banned && $active ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['banned' => false, 'active' => true]));
        $this->assertStringContainsString('pass', $result);

        $result = $this->render->compile($this->rawStub($raw), new Properties(['banned' => true, 'active' => true]));
        $this->assertStringContainsString('fail', $result);
    }

    public function testNegationWithLogicalOr(): void
    {
        $raw = '
        <{ if( !$a || !$b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => false, 'b' => true]));
        $this->assertStringContainsString('pass', $result);

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => true]));
        $this->assertStringContainsString('fail', $result);
    }

    public function testNegationWithSpaces(): void
    {
        $raw = '
        <{ if( ! $disabled ) }>
            enabled
        <{ else }>
            disabled
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['disabled' => false]));
        $this->assertStringContainsString('enabled', $result);
    }
}
