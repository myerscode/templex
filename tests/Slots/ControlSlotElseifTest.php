<?php

declare(strict_types=1);

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

final class ControlSlotElseifTest extends TestCase
{
    public function testBasicElseif(): void
    {
        $raw = '
        <{ if( $role === "admin" ) }>
            Admin
        <{ elseif( $role === "editor" ) }>
            Editor
        <{ else }>
            Guest
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'editor']));
        $this->assertStringContainsString('Editor', $result);
        $this->assertStringNotContainsString('Admin', $result);
        $this->assertStringNotContainsString('Guest', $result);
    }

    public function testElseifFirstBranchMatches(): void
    {
        $raw = '
        <{ if( $role === "admin" ) }>
            Admin
        <{ elseif( $role === "editor" ) }>
            Editor
        <{ else }>
            Guest
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'admin']));
        $this->assertStringContainsString('Admin', $result);
        $this->assertStringNotContainsString('Editor', $result);
        $this->assertStringNotContainsString('Guest', $result);
    }

    public function testElseifFallsToElse(): void
    {
        $raw = '
        <{ if( $role === "admin" ) }>
            Admin
        <{ elseif( $role === "editor" ) }>
            Editor
        <{ else }>
            Guest
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['role' => 'viewer']));
        $this->assertStringContainsString('Guest', $result);
        $this->assertStringNotContainsString('Admin', $result);
        $this->assertStringNotContainsString('Editor', $result);
    }

    public function testMultipleElseifBranches(): void
    {
        $raw = '
        <{ if( $level === 1 ) }>
            One
        <{ elseif( $level === 2 ) }>
            Two
        <{ elseif( $level === 3 ) }>
            Three
        <{ else }>
            Other
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['level' => 3]));
        $this->assertStringContainsString('Three', $result);
        $this->assertStringNotContainsString('One', $result);
        $this->assertStringNotContainsString('Two', $result);
        $this->assertStringNotContainsString('Other', $result);
    }

    public function testElseifWithoutElse(): void
    {
        $raw = '
        <{ if( $val === 1 ) }>
            One
        <{ elseif( $val === 2 ) }>
            Two
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['val' => 2]));
        $this->assertStringContainsString('Two', $result);
        $this->assertStringNotContainsString('One', $result);
    }

    public function testElseifNoMatchNoElse(): void
    {
        $raw = '
        <{ if( $val === 1 ) }>
            One
        <{ elseif( $val === 2 ) }>
            Two
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['val' => 99]));
        $this->assertStringNotContainsString('One', $result);
        $this->assertStringNotContainsString('Two', $result);
    }

    public function testElseifWithVariableComparison(): void
    {
        $raw = '
        <{ if( $status === "active" ) }>
            Active
        <{ elseif( $status === "pending" ) }>
            Pending
        <{ elseif( $status === "disabled" ) }>
            Disabled
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['status' => 'pending']));
        $this->assertStringContainsString('Pending', $result);
    }

    public function testElseifWithBooleanSelf(): void
    {
        $raw = '
        <{ if( $admin ) }>
            Admin
        <{ elseif( $editor ) }>
            Editor
        <{ else }>
            User
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['admin' => false, 'editor' => true]));
        $this->assertStringContainsString('Editor', $result);
        $this->assertStringNotContainsString('Admin', $result);
        $this->assertStringNotContainsString('User', $result);
    }

    public function testNestedIfInsideElseif(): void
    {
        $raw = '
        <{ if( $type === "a" ) }>
            Type A
        <{ elseif( $type === "b" ) }>
            <{ if( $sub === 1 ) }>
                B-1
            <{ else }>
                B-other
            <{ endif }>
        <{ else }>
            Unknown
        <{ endif }>
        ';

        $result = $this->render->compile($this->rawStub($raw), new Properties(['type' => 'b', 'sub' => 1]));
        $this->assertStringContainsString('B-1', $result);
        $this->assertStringNotContainsString('Type A', $result);
        $this->assertStringNotContainsString('Unknown', $result);
    }
}
