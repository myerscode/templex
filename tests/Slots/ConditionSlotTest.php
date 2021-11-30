<?php

namespace Tests\Slots;

use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Tests\TestCase;

class ConditionSlotTest extends TestCase
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
}
