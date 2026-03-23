<?php

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

class TernarySlotTest extends TestCase
{
    public function testNullCoalescingWithMissingVariable(): void
    {
        $raw = '<{ $title ?? "Untitled" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('Untitled', $result);
    }

    public function testNullCoalescingWithExistingVariable(): void
    {
        $raw = '<{ $title ?? "Untitled" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['title' => 'My Page']));

        $this->assertSame('My Page', $result);
    }

    public function testNullCoalescingWithSingleQuotes(): void
    {
        $raw = "<{ \$name ?? 'Anonymous' }>";
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('Anonymous', $result);
    }

    public function testNullCoalescingWithNumericDefault(): void
    {
        $raw = '<{ $count ?? 0 }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('0', $result);
    }

    public function testNullCoalescingWithVariableDefault(): void
    {
        $raw = '<{ $primary ?? $fallback }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['fallback' => 'backup']));

        $this->assertSame('backup', $result);
    }

    public function testNullCoalescingWithVariableDefaultBothExist(): void
    {
        $raw = '<{ $primary ?? $fallback }>';
        $result = $this->render->compile(
            $this->rawStub($raw),
            new Properties(['primary' => 'main', 'fallback' => 'backup']),
        );

        $this->assertSame('main', $result);
    }

    public function testNullCoalescingWithMissingVariableDefault(): void
    {
        $raw = '<{ $primary ?? $fallback }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('', $result);
    }

    public function testTernaryWithTruthyVariable(): void
    {
        $raw = '<{ $active ? "yes" : "no" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => true]));

        $this->assertSame('yes', $result);
    }

    public function testTernaryWithFalsyVariable(): void
    {
        $raw = '<{ $active ? "yes" : "no" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => false]));

        $this->assertSame('no', $result);
    }

    public function testTernaryWithTruthyString(): void
    {
        $raw = '<{ $name ? "has name" : "anonymous" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['name' => 'Fred']));

        $this->assertSame('has name', $result);
    }

    public function testTernaryWithEmptyString(): void
    {
        $raw = '<{ $name ? "has name" : "anonymous" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['name' => '']));

        $this->assertSame('anonymous', $result);
    }

    public function testTernaryWithMissingVariable(): void
    {
        $raw = '<{ $missing ? "found" : "not found" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('not found', $result);
    }

    public function testTernaryWithFalsyStringZero(): void
    {
        $raw = '<{ $val ? "truthy" : "falsy" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['val' => '0']));

        $this->assertSame('falsy', $result);
    }

    public function testTernaryWithFalsyStringFalse(): void
    {
        $raw = '<{ $val ? "truthy" : "falsy" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['val' => 'false']));

        $this->assertSame('falsy', $result);
    }

    public function testTernaryWithTruthyNumber(): void
    {
        $raw = '<{ $count ? "has items" : "empty" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['count' => 5]));

        $this->assertSame('has items', $result);
    }

    public function testTernaryWithZeroNumber(): void
    {
        $raw = '<{ $count ? "has items" : "empty" }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['count' => 0]));

        $this->assertSame('empty', $result);
    }

    public function testTernaryWithVariableValues(): void
    {
        $raw = '<{ $active ? $onLabel : $offLabel }>';
        $result = $this->render->compile(
            $this->rawStub($raw),
            new Properties(['active' => true, 'onLabel' => 'Enabled', 'offLabel' => 'Disabled']),
        );

        $this->assertSame('Enabled', $result);
    }

    public function testTernaryWithSingleQuotes(): void
    {
        $raw = "<{ \$active ? 'on' : 'off' }>";
        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => true]));

        $this->assertSame('on', $result);
    }

    public function testMultipleTernariesInTemplate(): void
    {
        $raw = '<{ $a ? "yes" : "no" }> and <{ $b ? "yes" : "no" }>';
        $result = $this->render->compile(
            $this->rawStub($raw),
            new Properties(['a' => true, 'b' => false]),
        );

        $this->assertSame('yes and no', $result);
    }

    public function testNullCoalescingAndTernaryTogether(): void
    {
        $raw = 'Title: <{ $title ?? "Untitled" }> Status: <{ $active ? "on" : "off" }>';
        $result = $this->render->compile(
            $this->rawStub($raw),
            new Properties(['active' => true]),
        );

        $this->assertSame('Title: Untitled Status: on', $result);
    }

    public function testTernaryInsideForeach(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $enabled ? "on" : "off" }>:<{ $item }>
        <{ endforeach }>
        ';

        $data = ['items' => ['a', 'b'], 'enabled' => true];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('on:a', $result);
        $this->assertStringContainsString('on:b', $result);
    }

    public function testNullCoalescingInsideForeach(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $missing ?? "default" }>:<{ $item }>
        <{ endforeach }>
        ';

        $data = ['items' => ['x', 'y']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('default:x', $result);
        $this->assertStringContainsString('default:y', $result);
    }

    public function testTernaryInsideForLoop(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 3; $i++ ) }>
            <{ $loop_first ? "FIRST" : "other" }>:<{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertStringContainsString('FIRST:0', $result);
        $this->assertStringContainsString('other:1', $result);
        $this->assertStringContainsString('other:2', $result);
    }

    public function testNullCoalescingWithWhitespace(): void
    {
        $raw = '<{  $title  ??  "Default"  }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));

        $this->assertSame('Default', $result);
    }

    public function testTernaryWithWhitespace(): void
    {
        $raw = '<{  $active  ?  "yes"  :  "no"  }>';
        $result = $this->render->compile($this->rawStub($raw), new Properties(['active' => true]));

        $this->assertSame('yes', $result);
    }
}
