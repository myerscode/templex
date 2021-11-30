<?php

namespace Tests\Slots;

use Tests\TestCase;

class LoopSlotTest extends TestCase
{

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
}
