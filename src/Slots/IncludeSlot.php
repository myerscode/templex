<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class IncludeSlot extends Slot
{
    public function process(string $template, Properties $variables): string
    {
        $regex = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'include\s(.+?)',
            '\s*',
            Templex::PLACEHOLDER_CLOSE,
            '\s*',
            '/i',
        ];

        return (string) preg_replace_callback(
            implode('', $regex),
            function (array $matches) use ($variables): string {
                $include = $matches[1];
                if ($this->engine->isTemplate($include)) {
                    return $this->engine->getTemplate($include);
                }

                throw new TemplateNotFoundException(sprintf('Could not include %s, as template not found', $include));
            },
            $template
        );
    }
}
