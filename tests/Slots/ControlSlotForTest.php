<?php

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

class ControlSlotForTest extends TestCase
{
    public function testRenderWithForLoop(): void
    {
        $data = [
            'start' => 5,
            'end' => 7,
        ];

        $result = $this->render->render('for-loop.stub', $data);

        $this->assertEquals($this->expectedContent('for-loop.stub'), $result);
    }

    public function testBasicForLoop(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 3; $i++ ) }>
            Item <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Item 0', $result);
        $this->assertStringContainsString('Item 1', $result);
        $this->assertStringContainsString('Item 2', $result);
        $this->assertStringNotContainsString('Item 3', $result);
    }

    public function testForLoopWithDecrement(): void
    {
        $raw = '
        <{ for( $i = 3; $i > 0; $i-- ) }>
            Count: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Count: 3', $result);
        $this->assertStringContainsString('Count: 2', $result);
        $this->assertStringContainsString('Count: 1', $result);
        $this->assertStringNotContainsString('Count: 0', $result);
    }

    public function testForLoopWithStep(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 6; $i += 2 ) }>
            Even: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Even: 0', $result);
        $this->assertStringContainsString('Even: 2', $result);
        $this->assertStringContainsString('Even: 4', $result);
        $this->assertStringNotContainsString('Even: 1', $result);
        $this->assertStringNotContainsString('Even: 6', $result);
    }

    public function testForLoopWithVariables(): void
    {
        $raw = '
        <{ for( $i = $start; $i <= $end; $i++ ) }>
            Value: <{ $i }>
        <{ endfor }>
        ';

        $data = ['start' => 2, 'end' => 4];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Value: 2', $result);
        $this->assertStringContainsString('Value: 3', $result);
        $this->assertStringContainsString('Value: 4', $result);
        $this->assertStringNotContainsString('Value: 1', $result);
        $this->assertStringNotContainsString('Value: 5', $result);
    }

    public function testNestedForLoops(): void
    {
        $raw = '
        <{ for( $i = 1; $i <= 2; $i++ ) }>
            <{ for( $j = 1; $j <= 2; $j++ ) }>
                <{ $i }>-<{ $j }>
            <{ endfor }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('1-1', $result);
        $this->assertStringContainsString('1-2', $result);
        $this->assertStringContainsString('2-1', $result);
        $this->assertStringContainsString('2-2', $result);
    }

    public function testForLoopWithDifferentOperators(): void
    {
        // Test <= operator
        $raw = '
        <{ for( $i = 1; $i <= 3; $i++ ) }>
            <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('3', $result);

        // Test >= operator
        $raw = '
        <{ for( $i = 3; $i >= 1; $i-- ) }>
            <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('3', $result);
        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('1', $result);
    }

    public function testForLoopWithSubtraction(): void
    {
        $raw = '
        <{ for( $i = 10; $i > 5; $i -= 2 ) }>
            Value: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Value: 10', $result);
        $this->assertStringContainsString('Value: 8', $result);
        $this->assertStringContainsString('Value: 6', $result);
        $this->assertStringNotContainsString('Value: 4', $result);
    }
}