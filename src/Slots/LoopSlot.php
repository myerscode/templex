<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class LoopSlot extends Slot
{

    protected int $structureDepthCounter = 0;

    protected array $levelCounter = [];

    public function process(string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            '(?<structure>(end)?foreach)',
            '/i',
        ];

        $mappedTemplate = preg_replace_callback(implode($regexParts), function (array $matches): string {

            $this->levelCounter[$this->structureDepthCounter] = $this->levelCounter[$this->structureDepthCounter] ?? 0;
            $isEndTag = str_starts_with($matches['structure'], 'end');

            if ($isEndTag) {
                $this->structureDepthCounter--;
            } else {
                $this->levelCounter[$this->structureDepthCounter]++;
            }

            $level = $this->levelCounter[$this->structureDepthCounter];

            $depth = $isEndTag ? $this->structureDepthCounter : $this->structureDepthCounter++;

            return sprintf(
                '%s-%s-%s',
                $matches[0],
                $level,
                $depth
            );
        },
            $template);

        return $this->resolveLoops($mappedTemplate, $variables);
    }

    protected function resolveLoops(string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'foreach(?<index>-[\d]+-[\d])\(',
            '\s*',
            '\$(?<variable>\w+)',
            '\s+as\s+',
            '\$(?<value>\w+)',
            '\s*\)\s*',
            Templex::PLACEHOLDER_CLOSE,
            '\s*(?<body>.+)\s?',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'endforeach\k<index>',
            '\s*',
            Templex::PLACEHOLDER_CLOSE,
            '/si',
        ];

        return preg_replace_callback(implode($regexParts),
            function (array $matches) use ($variables): string {
                $output = '';
                foreach ($variables->resolveValue($matches) as $value) {
                    $scope = array_merge(
                        $variables->variables(),
                        [$matches['value'] => $value]
                    );

                    $output .= $this->engine->process($this->resolveLoops($matches['body'], new Properties($scope)), new Properties($scope));
                }

                // this removes trailing space and breaks into a new line, left over from the placeholder closer
                return trim($output);
            },
            $template
        );
    }
}
