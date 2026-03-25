<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Properties;

interface SlotInterface
{
    public function process(string $template, Properties $properties): string;
}
