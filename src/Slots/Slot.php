<?php

declare(strict_types=1);

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\StubManager;

abstract class Slot implements SlotInterface
{
    public function __construct(protected StubManager $engine)
    {
    }
}
