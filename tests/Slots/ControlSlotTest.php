<?php

namespace Tests\Slots;

use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Tests\TestCase;

class ControlSlotTest extends TestCase
{

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

    public function testThrowsErrorOnUnmatchedComparisons()
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
