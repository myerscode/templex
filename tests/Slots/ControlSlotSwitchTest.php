<?php

namespace Tests\Slots;

use Myerscode\Templex\Properties;
use Tests\TestCase;

class ControlSlotSwitchTest extends TestCase
{
    public function testRenderWithSwitchStatement(): void
    {
        $data = [
            'status' => 'active',
            'number' => 1,
            'boolean' => true,
        ];

        $result = $this->render->render('switch.stub', $data);

        $this->assertEquals($this->expectedContent('switch.stub'), $result);
    }

    public function testSwitchWithStringCases(): void
    {
        $raw = '
        <{ switch( $status ) }>
            <{ case( "pending" ) }>
                Waiting for approval
            <{ case( "approved" ) }>
                Ready to go
            <{ case( "rejected" ) }>
                Not approved
            <{ default }>
                Unknown status
        <{ endswitch }>
        ';

        $data = ['status' => 'approved'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Ready to go', $result);

        $data = ['status' => 'pending'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Waiting for approval', $result);

        $data = ['status' => 'unknown'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Unknown status', $result);
    }

    public function testSwitchWithNumericCases(): void
    {
        $raw = '
        <{ switch( $level ) }>
            <{ case( 1 ) }>
                Level One
            <{ case( 2 ) }>
                Level Two
            <{ case( 3 ) }>
                Level Three
            <{ default }>
                Unknown Level
        <{ endswitch }>
        ';

        $data = ['level' => 2];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Level Two', $result);

        $data = ['level' => 99];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Unknown Level', $result);
    }

    public function testSwitchWithBooleanCases(): void
    {
        $raw = '
        <{ switch( $enabled ) }>
            <{ case( true ) }>
                Feature is enabled
            <{ case( false ) }>
                Feature is disabled
        <{ endswitch }>
        ';

        $data = ['enabled' => true];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Feature is enabled', $result);

        $data = ['enabled' => false];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Feature is disabled', $result);
    }

    public function testSwitchWithoutDefault(): void
    {
        $raw = '
        <{ switch( $type ) }>
            <{ case( "admin" ) }>
                Administrator
            <{ case( "user" ) }>
                Regular User
        <{ endswitch }>
        ';

        $data = ['type' => 'admin'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Administrator', $result);

        $data = ['type' => 'guest'];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringNotContainsString('Administrator', $result);
        $this->assertStringNotContainsString('Regular User', $result);
    }

    public function testSwitchWithVariableCases(): void
    {
        $raw = '
        <{ switch( $userRole ) }>
            <{ case( $adminRole ) }>
                Admin Access
            <{ case( $moderatorRole ) }>
                Moderator Access
            <{ default }>
                Basic Access
        <{ endswitch }>
        ';

        $data = [
            'userRole' => 'admin',
            'adminRole' => 'admin',
            'moderatorRole' => 'moderator'
        ];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Admin Access', $result);

        $data = [
            'userRole' => 'moderator',
            'adminRole' => 'admin',
            'moderatorRole' => 'moderator'
        ];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Moderator Access', $result);
    }

    public function testNestedSwitchStatements(): void
    {
        $raw = '
        <{ switch( $category ) }>
            <{ case( "electronics" ) }>
                <{ switch( $subcategory ) }>
                    <{ case( "phones" ) }>
                        Mobile Phones
                    <{ case( "laptops" ) }>
                        Laptop Computers
                    <{ default }>
                        Other Electronics
                <{ endswitch }>
            <{ case( "books" ) }>
                <{ switch( $subcategory ) }>
                    <{ case( "fiction" ) }>
                        Fiction Books
                    <{ case( "nonfiction" ) }>
                        Non-Fiction Books
                    <{ default }>
                        Other Books
                <{ endswitch }>
            <{ default }>
                Unknown Category
        <{ endswitch }>
        ';

        $data = [
            'category' => 'electronics',
            'subcategory' => 'phones'
        ];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Mobile Phones', $result);

        $data = [
            'category' => 'books',
            'subcategory' => 'fiction'
        ];
        $result = $this->render->compile($this->rawStub($raw), new Properties($data));
        $this->assertStringContainsString('Fiction Books', $result);
    }

    public function testComprehensiveSwitchExample(): void
    {
        $data = [
            'role' => 'admin',
            'priority' => 2,
            'enabled' => true,
            'category' => 'tech',
            'subcategory' => 'software',
            'userType' => 'admin',
            'adminType' => 'admin',
            'guestType' => 'guest'
        ];

        $result = $this->render->render('switch-comprehensive.stub', $data);

        $this->assertEquals($this->expectedContent('switch-comprehensive.stub'), $result);
    }
}