<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class VariableSlot extends Slot
{
    public function process(string $template, Properties $variables): string
    {
        $placeHolderRegex = '\$(?<variable>\w+)?';

        $regex = '/' . Templex::PLACEHOLDER_OPEN . '\s*' . $placeHolderRegex . '\s*' . Templex::PLACEHOLDER_CLOSE . '/i';

        return (string) preg_replace_callback(
            $regex,
            function (array $matches) use ($variables): string {
                return $variables->resolveValue($matches);
            },
            $template
        );
    }
}
