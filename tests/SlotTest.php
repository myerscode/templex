<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Templex\Slots\ControlSlot;

final class SlotTest extends TestCase
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

        $this->assertSame($this->expectedContent('nested-slots.stub'), $result);
    }
}
