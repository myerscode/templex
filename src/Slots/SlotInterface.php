<?php

declare(strict_types=1);

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Properties;

interface SlotInterface
{
    public function process(string $template, Properties $properties): string;
}
