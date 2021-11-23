<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

interface SlotInterface
{
    public function process(string $template, Properties $variables): string;
}
