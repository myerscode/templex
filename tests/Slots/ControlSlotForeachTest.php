<?php

declare(strict_types=1);

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

final class ControlSlotForeachTest extends TestCase
{
    public function testForeachWithKeyValue(): void
    {
        $raw = '
        <{ foreach( $users as $id => $name ) }>
            <{ $id }>: <{ $name }>
        <{ endforeach }>
        ';

        $data = ['users' => ['admin' => 'Fred', 'mod' => 'Chris', 'user' => 'Tor']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('admin: Fred', $result);
        $this->assertStringContainsString('mod: Chris', $result);
        $this->assertStringContainsString('user: Tor', $result);
    }

    public function testForeachWithNumericKeys(): void
    {
        $raw = '
        <{ foreach( $items as $index => $item ) }>
            <{ $index }>-<{ $item }>
        <{ endforeach }>
        ';

        $data = ['items' => ['apple', 'banana', 'cherry']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('0-apple', $result);
        $this->assertStringContainsString('1-banana', $result);
        $this->assertStringContainsString('2-cherry', $result);
    }

    public function testForeachWithoutKeyStillWorks(): void
    {
        $raw = '
        <{ foreach( $names as $name ) }>
            Hello <{ $name }>
        <{ endforeach }>
        ';

        $data = ['names' => ['Fred', 'Chris']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('Hello Fred', $result);
        $this->assertStringContainsString('Hello Chris', $result);
    }

    public function testForeachKeyValueWithCondition(): void
    {
        $raw = '
        <{ foreach( $roles as $user => $role ) }>
            <{ if( $role === "admin" ) }>
                <{ $user }> is an admin
            <{ else }>
                <{ $user }> is a <{ $role }>
            <{ endif }>
        <{ endforeach }>
        ';

        $data = ['roles' => ['Fred' => 'admin', 'Chris' => 'editor', 'Tor' => 'viewer']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('Fred is an admin', $result);
        $this->assertStringContainsString('Chris is a editor', $result);
        $this->assertStringContainsString('Tor is a viewer', $result);
    }

    public function testNestedForeachWithKeys(): void
    {
        $raw = '
        <{ foreach( $groups as $group => $members ) }>
            Group: <{ $group }>
            <{ foreach( $members as $member ) }>
                - <{ $member }>
            <{ endforeach }>
        <{ endforeach }>
        ';

        $data = [
            'groups' => [
                'devs' => ['Fred', 'Chris'],
                'ops' => ['Tor'],
            ],
        ];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('Group: devs', $result);
        $this->assertStringContainsString('Group: ops', $result);
        $this->assertStringContainsString('- Fred', $result);
        $this->assertStringContainsString('- Chris', $result);
        $this->assertStringContainsString('- Tor', $result);
    }

    public function testForeachKeyValueWithWhitespaceVariations(): void
    {
        $raw = '
        <{ foreach( $data as $k=>$v ) }>
            <{ $k }>=<{ $v }>
        <{ endforeach }>
        ';

        $data = ['data' => ['a' => '1', 'b' => '2']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('a=1', $result);
        $this->assertStringContainsString('b=2', $result);
    }

    public function testForeachKeyValueWithExtraSpaces(): void
    {
        $raw = '
        <{ foreach( $data as $k  =>  $v ) }>
            <{ $k }>=<{ $v }>
        <{ endforeach }>
        ';

        $data = ['data' => ['x' => '9']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('x=9', $result);
    }

    public function testForeachKeyDoesNotLeakToOuterScope(): void
    {
        $raw = '
        <{ foreach( $items as $key => $val ) }>
            <{ $key }>
        <{ endforeach }>
        ';

        $data = ['items' => ['a' => 'one'], 'key' => 'outer'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('a', $result);
        $this->assertStringNotContainsString('outer', $result);
    }

    public function testRenderForeachKeyValueFromTemplate(): void
    {
        $data = [
            'settings' => ['theme' => 'dark', 'lang' => 'en', 'debug' => 'true'],
        ];

        $result = $this->render->compile(
            $this->rawStub('<{ foreach( $settings as $key => $value ) }><{ $key }>: <{ $value }> <{ endforeach }>'),
            new Properties($data),
        );

        $this->assertStringContainsString('theme: dark', $result);
        $this->assertStringContainsString('lang: en', $result);
        $this->assertStringContainsString('debug: true', $result);
    }

    public function testForeachLoopIndex(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $loop_index }>:<{ $item }>
        <{ endforeach }>
        ';

        $data = ['items' => ['a', 'b', 'c']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('0:a', $result);
        $this->assertStringContainsString('1:b', $result);
        $this->assertStringContainsString('2:c', $result);
    }

    public function testForeachLoopCount(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $item }> of <{ $loop_count }>
        <{ endforeach }>
        ';

        $data = ['items' => ['x', 'y', 'z']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('x of 3', $result);
        $this->assertStringContainsString('y of 3', $result);
        $this->assertStringContainsString('z of 3', $result);
    }

    public function testForeachLoopFirst(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ if( $loop_first ) }>first:<{ endif }><{ $item }>
        <{ endforeach }>
        ';

        $data = ['items' => ['a', 'b', 'c']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('first:a', $result);
        $this->assertStringNotContainsString('first:b', $result);
        $this->assertStringNotContainsString('first:c', $result);
    }

    public function testForeachLoopLast(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $item }><{ if( $loop_last ) }>:last<{ endif }>
        <{ endforeach }>
        ';

        $data = ['items' => ['a', 'b', 'c']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringNotContainsString('a:last', $result);
        $this->assertStringNotContainsString('b:last', $result);
        $this->assertStringContainsString('c:last', $result);
    }

    public function testForeachLoopMetadataWithSingleItem(): void
    {
        $raw = '
        <{ foreach( $items as $item ) }>
            <{ $loop_index }>-<{ $loop_count }>-<{ $loop_first }>-<{ $loop_last }>
        <{ endforeach }>
        ';

        $data = ['items' => ['only']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('0-1-1-1', $result);
    }

    public function testForeachLoopMetadataWithKeyValue(): void
    {
        $raw = '
        <{ foreach( $data as $k => $v ) }>
            <{ $loop_index }>:<{ $k }>=<{ $v }>
        <{ endforeach }>
        ';

        $data = ['data' => ['name' => 'Fred', 'role' => 'dev']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));

        $this->assertStringContainsString('0:name=Fred', $result);
        $this->assertStringContainsString('1:role=dev', $result);
    }
}
