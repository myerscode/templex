<?php

declare(strict_types=1);

namespace Tests\Slots;

use Iterator;
use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Myerscode\Templex\Properties;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class ControlSlotTest extends TestCase
{
    public static function falselyProvider(): Iterator
    {
        yield 'true string' => ['false'];
        yield 'true bool' => [false];
        yield 'true int' => [0];
    }
    public static function operatorProvider(): Iterator
    {
        yield '== pass' => ['==', "'a'", "'a'", 'pass'];
        yield '== pass 2' => ['==', "'abc'", "'abc'", 'pass'];
        yield '== fail' => ['==', "'a'", "'b'", 'fail'];
        yield '=== pass' => ['===', "'a'", "'a'", 'pass'];
        yield '=== pass 2' => ['===', "'abc'", "'abc'", 'pass'];
        yield '=== fail' => ['===', "'a'", "'b'", 'fail'];
        yield '!= pass' => ['!=', "'a'", "'a'", 'fail'];
        yield '!= pass 2' => ['!=', "'a'", "'b'", 'pass'];
        yield '!= pass 3' => ['!=', '7', 49, 'pass'];
        yield '!= pass 4' => ['!=', "'abc'", "'abc'", 'fail'];
        yield '!= fail' => ['!==', "'a'", "'b'", 'pass'];
        yield '!== pass' => ['!==', "'a'", "'a'", 'fail'];
        yield '!== pass 2' => ['!==', "'abc'", "'abc'", 'fail'];
        yield '!== fail' => ['!==', "'49'", 49, 'pass'];
        yield '> pass' => ['>', 49, 7, 'pass'];
        yield '> fail' => ['>', 7, 49, 'fail'];
        yield '>= equal pass' => ['>=', 7, 7, 'pass'];
        yield '>= pass' => ['>=', 49, 48, 'pass'];
        yield '>= fail' => ['>=', 7, 48, 'fail'];
        yield '< pass' => ['<', 7, 49, 'pass'];
        yield '< fail' => ['<', 49, 7, 'fail'];
        yield '<= pass' => ['<=', 7, 49, 'pass'];
        yield '<= pass equal' => ['<=', 49, 49, 'pass'];
        yield '<= fail' => ['<=', 49, 7, 'fail'];
    }

    public static function truthyProvider(): Iterator
    {
        yield 'true string' => ['true'];
        yield 'true bool' => [true];
        yield 'true int' => [1];
        yield 'word' => ['fred'];
        yield 'number' => [49];
    }

    public function testHandlesBoolComparison(): void
    {
        $raw = '
        <{ if( true ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $expected = '
        pass
        ';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertSame($expected, $result);

        $raw = '
        <{ if( false ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $expected = '
        fail
        ';
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertSame($expected, $result);
    }

    #[DataProvider('operatorProvider')]
    public function testHandlesComparisonOperators(string $operator, string|int $firstValue, string|int $secondValue, string $outcome): void
    {
        $raw = "
        <{ if( {$firstValue} {$operator} {$secondValue} ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ";

        $expected = "
        {$outcome}
        ";
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertSame($expected, $result);
    }

    public function testHandlesComparisons(): void
    {
        $data = [
            'name' => 'Fred',
            'value' => true,
            'trueValue' => true,
            'falseValue' => false,
            'selfTrueValue' => true,
            'selfFalseValue' => false,
            'a' => '1',
            'b' => '1',
            'c' => 1,
            'd' => 7,
            'e' => 7,
        ];

        $result = $this->render->render('condition.stub', $data);

        $this->assertSame($this->expectedContent('condition.stub'), $result);
    }

    #[DataProvider('falselyProvider')]
    public function testHandlesSelfFalselyComparison(string|bool|int $var): void
    {
        $raw = '
        <{ if ( $var ) }>
            Value was ' . $var . '
        <{ endif }>
        ';

        $expected = '
        
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['var' => $var]));
        $this->assertSame($expected, $result);
    }

    #[DataProvider('truthyProvider')]
    public function testHandlesSelfTruthyComparison(string|bool|int $var): void
    {
        $raw = '
        <{ if ( $var ) }>
            Value was ' . $var . '
        <{ endif }>
        ';

        $expected = '
        Value was ' . $var . '
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['var' => $var]));
        $this->assertSame($expected, $result);
    }

    public function testRenderWithSimpleLoop(): void
    {
        $data = [
            'Users' => ['Fred', 'Chris', 'Tor'],
            'abc' => ['a', 'b', 'c'],
            'xyz' => ['x', 'y', 'z'],
        ];

        $result = $this->render->render('loop.stub', $data);

        $this->assertSame($this->expectedContent('loop.stub'), $result);
    }

    public function testThrowsErrorOnUnmatchedComparisons(): void
    {
        $this->expectException(UnmatchedComparisonException::class);
        $this->render->render('unmatched-condition.stub');
    }

    public function testWhiteSpaceOfConditions(): void
    {
        $data = [
            'trueValue' => true,
            'falseValue' => false,
        ];

        $result = $this->render->render('white-space-condition', $data);

        $this->assertSame($this->expectedContent('white-space-condition.stub'), $result);
    }

    public function testWhiteSpaceThreshold(): void
    {
        $data = [
            'Users' => ['Fred', 'Chris', 'Tor'],
            'abc' => ['a', 'b', 'c'],
            'xyz' => ['x', 'y', 'z'],
        ];

        $result = $this->render->render('white-space-loop.stub', $data);

        $this->assertSame($this->expectedContent('loop.stub'), $result);
    }

}
