<?php

namespace Tests\Slots;

use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Myerscode\Templex\Properties;
use Tests\TestCase;

class ControlSlotTest extends TestCase
{
    public static function operatorProvider(): array
    {
        return [
            '== pass' => ['==', "'a'", "'a'", 'pass'],
            '== pass 2' => ['==', "'abc'", "'abc'", 'pass'],
            '== fail' => ['==', "'a'", "'b'", 'fail'],
            '=== pass' => ['===', "'a'", "'a'", 'pass'],
            '=== pass 2' => ['===', "'abc'", "'abc'", 'pass'],
            '=== fail' => ['===', "'a'", "'b'", 'fail'],
            '!= pass' => ['!=', "'a'", "'a'", 'fail'],
            '!= pass 2' => ['!=', "'a'", "'b'", 'pass'],
            '!= pass 3' => ['!=', '7', 49, 'pass'],
            '!= pass 4' => ['!=', "'abc'", "'abc'", 'fail'],
            '!= fail' => ['!==', "'a'", "'b'", 'pass'],
            '!== pass' => ['!==', "'a'", "'a'", 'fail'],
            '!== pass 2' => ['!==', "'abc'", "'abc'", 'fail'],
            '!== fail' => ['!==', "'49'", 49, 'pass'],
            '> pass' => ['>', 49, 7, 'pass'],
            '> fail' => ['>', 7, 49, 'fail'],
            '>= equal pass' => ['>=', 7, 7, 'pass'],
            '>= pass' => ['>=', 49, 48, 'pass'],
            '>= fail' => ['>=', 7, 48, 'fail'],
            '< pass' => ['<', 7, 49, 'pass'],
            '< fail' => ['<', 49, 7, 'fail'],
            '<= pass' => ['<=', 7, 49, 'pass'],
            '<= pass equal' => ['<=', 49, 49, 'pass'],
            '<= fail' => ['<=', 49, 7, 'fail'],
        ];
    }

    public static function __truthyProvider(): array
    {
        return [
            'true string' => ['true'],
            'true bool' => [true],
            'true int' => [1],
            'word' => ['fred'],
            'number' => [49],
        ];
    }

    public static function __falselyProvider(): array
    {
        return [
            'true string' => ['false'],
            'true bool' => [false],
            'true int' => [0],
        ];
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

        $this->assertEquals($this->expectedContent('condition.stub'), $result);
    }

    /**
     * @dataProvider __truthyProvider
     */
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
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider __falselyProvider
     */
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
        $this->assertEquals($expected, $result);
    }

    public function testHandlesBoolComparison(): void
    {
        $raw = "
        <{ if( true ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ";

        $expected = "
        pass
        ";
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertEquals($expected, $result);

        $raw = "
        <{ if( false ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ";

        $expected = "
        fail
        ";
        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider operatorProvider
     */
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
        $this->assertEquals($expected, $result);
    }

    public function testThrowsErrorOnUnmatchedComparisons(): void
    {
        $this->expectException(UnmatchedComparisonException::class);
        $this->render->render('unmatched-condition.stub');
    }

    public function testRenderWithSimpleLoop(): void
    {
        $data = [
            'Users' => ['Fred', 'Chris', 'Tor'],
            'abc' => ['a', 'b', 'c'],
            'xyz' => ['x', 'y', 'z'],
        ];

        $result = $this->render->render('loop.stub', $data);

        $this->assertEquals($this->expectedContent('loop.stub'), $result);
    }

    public function testWhiteSpaceThreshold(): void
    {
        $data = [
            'Users' => ['Fred', 'Chris', 'Tor'],
            'abc' => ['a', 'b', 'c'],
            'xyz' => ['x', 'y', 'z'],
        ];

        $result = $this->render->render('white-space-loop.stub', $data);

        $this->assertEquals($this->expectedContent('loop.stub'), $result);
    }

    public function testWhiteSpaceOfConditions(): void
    {
        $data = [
            'trueValue' => true,
            'falseValue' => false,
        ];

        $result = $this->render->render('white-space-condition', $data);

        $this->assertEquals($this->expectedContent('white-space-condition.stub'), $result);
    }
}
