<?php

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

class ControlSlotLogicalTest extends TestCase
{
    public function testAndBothTrue(): void
    {
        $raw = '
        <{ if( $a && $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => true]));
        $this->assertStringContainsString('pass', $result);
        $this->assertStringNotContainsString('fail', $result);
    }

    public function testAndFirstFalse(): void
    {
        $raw = '
        <{ if( $a && $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => false, 'b' => true]));
        $this->assertStringContainsString('fail', $result);
        $this->assertStringNotContainsString('pass', $result);
    }

    public function testAndSecondFalse(): void
    {
        $raw = '
        <{ if( $a && $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => false]));
        $this->assertStringContainsString('fail', $result);
    }

    public function testOrBothFalse(): void
    {
        $raw = '
        <{ if( $a || $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => false, 'b' => false]));
        $this->assertStringContainsString('fail', $result);
        $this->assertStringNotContainsString('pass', $result);
    }

    public function testOrFirstTrue(): void
    {
        $raw = '
        <{ if( $a || $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => false]));
        $this->assertStringContainsString('pass', $result);
    }

    public function testOrSecondTrue(): void
    {
        $raw = '
        <{ if( $a || $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => false, 'b' => true]));
        $this->assertStringContainsString('pass', $result);
    }

    public function testOrBothTrue(): void
    {
        $raw = '
        <{ if( $a || $b ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => true]));
        $this->assertStringContainsString('pass', $result);
    }

    public function testAndWithComparisons(): void
    {
        $raw = '
        <{ if( $age >= 18 && $role === "admin" ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['age' => 25, 'role' => 'admin']));
        $this->assertStringContainsString('pass', $result);

        $result = $this->render->compile($this->rawStub($raw), new Properties(['age' => 15, 'role' => 'admin']));
        $this->assertStringContainsString('fail', $result);
    }

    public function testOrWithComparisons(): void
    {
        $raw = '
        <{ if( $role === "admin" || $role === "editor" ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'editor']));
        $this->assertStringContainsString('pass', $result);

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'viewer']));
        $this->assertStringContainsString('fail', $result);
    }

    public function testTripleAnd(): void
    {
        $raw = '
        <{ if( $a && $b && $c ) }>
            pass
        <{ else }>
            fail
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => true, 'c' => true]));
        $this->assertStringContainsString('pass', $result);

        $result = $this->render->compile($this->rawStub($raw), new Properties(['a' => true, 'b' => true, 'c' => false]));
        $this->assertStringContainsString('fail', $result);
    }

    public function testLogicalOperatorsInElseif(): void
    {
        $raw = '
        <{ if( $role === "admin" ) }>
            Admin
        <{ elseif( $role === "editor" || $role === "author" ) }>
            Writer
        <{ else }>
            Guest
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'author']));
        $this->assertStringContainsString('Writer', $result);
        $this->assertStringNotContainsString('Admin', $result);
        $this->assertStringNotContainsString('Guest', $result);
    }

    public function testLogicalOperatorsInsideLoop(): void
    {
        $raw = '
        <{ foreach( $users as $user ) }>
            <{ if( $user === "admin" || $user === "root" ) }>
                SUPER:<{ $user }>
            <{ else }>
                USER:<{ $user }>
            <{ endif }>
        <{ endforeach }>
        ';

        $data = ['users' => ['admin', 'fred', 'root']];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('SUPER:admin', $result);
        $this->assertStringContainsString('USER:fred', $result);
        $this->assertStringContainsString('SUPER:root', $result);
    }
}
