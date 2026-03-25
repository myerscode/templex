<?php

declare(strict_types=1);

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

final class ControlSlotForTest extends TestCase
{
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

    public function testForLoopCount(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 4; $i++ ) }>
            <{ $loop_count }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('4', $result);
    }

    public function testForLoopFirstAndLast(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 3; $i++ ) }>
            <{ if( $loop_first ) }>FIRST<{ endif }><{ if( $loop_last ) }>LAST<{ endif }><{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('FIRST0', $result);
        $this->assertStringNotContainsString('FIRST1', $result);
        $this->assertStringNotContainsString('LAST0', $result);
        $this->assertStringContainsString('LAST2', $result);
    }

    public function testForLoopIndex(): void
    {
        $raw = '
        <{ for( $i = 10; $i < 13; $i++ ) }>
            <{ $loop_index }>:<{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('0:10', $result);
        $this->assertStringContainsString('1:11', $result);
        $this->assertStringContainsString('2:12', $result);
    }

    public function testForLoopMetadataWithSingleIteration(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 1; $i++ ) }>
            <{ $loop_index }>-<{ $loop_count }>-<{ $loop_first }>-<{ $loop_last }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('0-1-1-1', $result);
    }

    public function testForLoopMetadataWithStep(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 10; $i += 3 ) }>
            <{ $loop_index }>:<{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('0:0', $result);
        $this->assertStringContainsString('1:3', $result);
        $this->assertStringContainsString('2:6', $result);
        $this->assertStringContainsString('3:9', $result);
    }

    public function testForLoopWithBooleanLiterals(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 1; $i++ ) }>
            Done
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Done', $result);
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

    public function testForLoopWithEqualityOperator(): void
    {
        $raw = '
        <{ for( $i = 0; $i == 0; $i++ ) }>
            Once: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Once: 0', $result);
        $this->assertStringNotContainsString('Once: 1', $result);
    }

    public function testForLoopWithNotEqualOperator(): void
    {
        $raw = '
        <{ for( $i = 0; $i != 2; $i++ ) }>
            NE: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('NE: 0', $result);
        $this->assertStringContainsString('NE: 1', $result);
        $this->assertStringNotContainsString('NE: 2', $result);
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

    public function testForLoopWithStrictEqualityOperator(): void
    {
        $raw = '
        <{ for( $i = 0; $i === 0; $i++ ) }>
            Strict: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Strict: 0', $result);
    }

    public function testForLoopWithStrictNotEqualOperator(): void
    {
        $raw = '
        <{ for( $i = 0; $i !== 2; $i++ ) }>
            SNE: <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('SNE: 0', $result);
        $this->assertStringContainsString('SNE: 1', $result);
        $this->assertStringNotContainsString('SNE: 2', $result);
    }

    public function testForLoopWithStringLiteralValue(): void
    {
        $raw = '
        <{ for( $i = 0; $i < 2; $i++ ) }>
            Item <{ $i }>
        <{ endfor }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties([]));
        $this->assertStringContainsString('Item 0', $result);
        $this->assertStringContainsString('Item 1', $result);
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
    public function testRenderWithForLoop(): void
    {
        $data = [
            'start' => 5,
            'end' => 7,
        ];

        $result = $this->render->render('for-loop.stub', $data);

        $this->assertSame($this->expectedContent('for-loop.stub'), $result);
    }
}
