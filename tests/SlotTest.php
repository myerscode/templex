<?php

namespace Tests;

use Myerscode\Templex\Slots\ConditionSlot;
use Myerscode\Templex\Slots\ControlSlot;

class SlotTest extends TestCase
{
    public function testCanRenderNestedSlots(): void
    {
        $data = [
            'Users' => [
                'Fred',
                'Chris',
                'Tor',
            ],
            'boolean' => 'true',
        ];
        $this->render->setSlots([ControlSlot::class]);

        $result = $this->render->render('nested-slots', $data);

        $this->assertEquals($this->expectedContent('nested-slots.stub'), $result);
    }
}
