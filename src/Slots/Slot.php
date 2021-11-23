<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Templex;

abstract class Slot implements SlotInterface
{
    public function __construct(protected Templex $engine)
    {
    }
}
