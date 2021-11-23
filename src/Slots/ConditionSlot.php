<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class ConditionSlot extends Slot
{
    protected int $structureDepthCounter = 0;

    protected array $levelCounter = [];

    public function process(string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            '(?<structure>((end)?if|else))',
            '/i',
        ];

        $mappedTemplate = preg_replace_callback(implode($regexParts), function (array $matches): string {

            $this->levelCounter[$this->structureDepthCounter] = $this->levelCounter[$this->structureDepthCounter] ?? 0;

            if ($matches['structure'] === 'else') {
                return sprintf(
                    '%s-%s-%s',
                    $matches[0],
                    $this->levelCounter[$this->structureDepthCounter - 1],
                    ($this->structureDepthCounter - 1)
                );
            }


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

        return $this->resolveConditions($mappedTemplate, $variables);
    }

    public function resolveConditions(string $template, Properties $variables): string
    {

        // <{\s*if(?<index>-[\d]+-[\d])\(\s*\$(?<variable>.+)\s*\)\s*}>(?<body>.+)<{\s*endif\k<index>\s*}>
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'if(?<index>-[\d]+-[\d])\(',
            '\s*',
            '(?<variable>.+)',
            '\s*',
            '\)',
            '\s*',
            Templex::PLACEHOLDER_CLOSE,
            '(?<body>.+)',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'endif\k<index>',
            '\s*',
            Templex::PLACEHOLDER_CLOSE,
            '/si',
        ];

        return preg_replace_callback(
            implode($regexParts),
            function (array $matches) use ($variables): string {

                $elseRegex = [
                    '/',
                    Templex::PLACEHOLDER_OPEN,
                    '\s*',
                    "else{$matches['index']}",
                    '\s*',
                    Templex::PLACEHOLDER_CLOSE,
                    '/si',
                ];

                $conditionalBody = preg_split(implode($elseRegex), $matches['body']);

                $condition = $this->resolveCondition(trim($matches['variable']), $variables);

                if ($condition) {
                    return trim($conditionalBody[0]);
                }

                return trim($conditionalBody[1] ?? '');
            },
            $template,
        );
    }


    /**
     * @throws \Myerscode\Templex\Exceptions\VariableNotFoundException
     * @throws UnmatchedComparisonException
     */
    protected function resolveCondition(string $condition, Properties $variables): bool
    {
        $simpleComparisonRegex = [
            'self' => '/^\$(?<variable>\w+)\s?$/si',
            'comparison' =>
                '/^((?<first_is_literal>[\"\'])|(?<first_is_variable>[\$])){1}(?<first_value>\w+)\k<first_is_literal>?'.
                '(?:\s?)(?<operators>[=!><]+)(?:\s?)'.
                '((?<second_is_literal>[\"\'])|(?<second_is_variable>\$))?(?<second_value>\w+)\k<second_is_literal>?$/si',
        ];

        foreach ($simpleComparisonRegex as $conditionType => $comparisonRegex) {
            if (preg_match($comparisonRegex, $condition, $matches, PREG_UNMATCHED_AS_NULL)) {
                switch ($conditionType) {
                    case 'self':
                        return boolval($variables->resolveValue(['variable' => $matches['variable']]));
                    case 'comparison':
                        return $this->resolveComparison($matches, $variables);
                }
            }
        }

        throw new UnmatchedComparisonException("Unable to resolve condition \"$condition\"");
    }

    protected function resolveComparison($matches, $variables)
    {

        $firstIsLiteral = isset($matches['first_is_literal']);
        $firstIsVar = isset($matches['first_is_variable']);

        $secondIsLiteral = isset($matches['second_is_literal']);
        $secondIsVar = isset($matches['second_is_variable']);

        $operation = $matches['operators'];

        if ($firstIsVar) {
            $firstValue = $variables->resolveValue(['variable' => $matches['first_value']]);
        } else {
            if ($firstIsLiteral) {
                $firstValue = $matches['first_value'];
            }
        }

        if ($secondIsVar) {
            $secondValue = $variables->resolveValue(['variable' => $matches['second_value']]);
        } else {
            if ($secondIsLiteral) {
                $secondValue = $matches['second_value'];
            }
        }


        return $this->operatorTranslation($firstValue, $operation, $secondValue);
    }

    protected function operatorTranslation($a, $operation, $b): bool
    {
        return match ($operation) {
            '==' => $a == $b,
            '===' => $a === $b,
            '!=' => $a != $b,
            '!==' => $a !== $b,
            '>' => $a > $b,
            '>=' => $a >= $b,
            '<=' => $a <= $b,
            default => throw new \Exception('Unknown comparison operator'),
        };
    }
}
