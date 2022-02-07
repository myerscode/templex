<?php

namespace Tests\Slots;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Tests\TestCase;

class IncludeSlotTest extends TestCase
{
    public function testIncludesTemplate(): void
    {
        $result = $this->render->render('include-example');

        $this->assertEquals($this->expectedContent('include-example.stub'), $result);
    }

    public function testTrowsErrorIfIncludeNotFound(): void
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->expectExceptionMessage('Could not include partials.footer, as template not found');
        $this->render->render('include-fail');
    }
}
