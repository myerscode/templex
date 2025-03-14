<?php

namespace Myerscode\Templex\Slots;

use Exception;
use Myerscode\Templex\Exceptions\VariableNotFoundException;
use Myerscode\Templex\Exceptions\UnmatchedComparisonException;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class ControlSlot extends Slot
{

    protected int $depthCounter = 0;

    protected array $levelCounter = [];

    public function process(string $template, Properties $variables): string
    {
        $indexedTemplate = $this->indexControls($template);

        return $this->processIndexes($indexedTemplate, $variables);
    }

    protected function indexControls(string $template): string
    {
        $controlStructureRegexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            '(?<structure>(',
            '(?:end)?if|else|',
            '(?:end)?foreach|',
            '(?:end)?for|',
            '(?:end)?switch',
            '))',
            '/i',
        ];

        $lastIf = [];

        return preg_replace_callback(implode('', $controlStructureRegexParts), function (array $matches) use (&$lastIf): string {

            if ($matches['structure'] === 'else') {
                return sprintf(
                    '%s-%s-%s',
                    $matches[0],
                    array_pop($lastIf),
                    ($this->depthCounter - 1)
                );
            }

            $this->levelCounter[$this->depthCounter] ??= 0;

            $isEndTag = str_starts_with($matches['structure'], 'end');

            if ($isEndTag) {
                $this->depthCounter--;
            } else {
                $this->levelCounter[$this->depthCounter]++;
            }

            $level = ($this->depthCounter > 0) ? $this->levelCounter[$this->depthCounter] + 1 : $this->levelCounter[$this->depthCounter];

            $depth = $isEndTag ? $this->depthCounter : $this->depthCounter++;

            // take note of the level if the if is on, so can reference when a else is found
            if ($matches['structure'] === 'if') {
                $lastIf[] = $level;
            }

            return sprintf(
                '%s-%s-%s',
                $matches[0],
                $level,
                $depth
            );
        },
            $template);
    }

    protected function processIndexes(string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            '(?<control>if|foreach|for|switch)(?<index>-[\d]+-[\d])',
            '/si',
        ];

        $matches = [];

        preg_match(implode('', $regexParts), $template, $matches);

        if ($matches !== []) {
            $control = $matches['control'];
            $index = $matches['index'];
            $template = $this->resolveControl($control, $index, $template, $variables);
            $template = $this->processIndexes($template, $variables);
        }

        return $template;
    }

    protected function resolveControl(string $control, string $index, string $template, Properties $variables): string
    {
        switch ($control) {
            case 'foreach':
                return $this->resolveForEach($index, $template, $variables);
            case 'if':
                return $this->resolveIfStatement($index, $template, $variables);
        }
    }

    protected function resolveForEach(string $index, string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'foreach(?<index>' . $index . ')\(',
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

        return preg_replace_callback(
            implode('', $regexParts),
            function (array $matches) use ($variables): string {
                $output = '';
                foreach ($variables->resolveValue($matches) as $value) {
                    $scope = array_merge(
                        $variables->variables(),
                        [$matches['value'] => $value]
                    );
                    $template = $this->processIndexes($matches['body'], new Properties($scope));
                    $output .= (new VariableSlot($this->engine))->process($template, new Properties($scope));
                }

                // this removes trailing space and breaks into a new line, left over from the placeholder closer
                return trim($output);
            },
            $template
        );
    }

    protected function resolveIfStatement(string $index, string $template, Properties $variables): string
    {
        $regexParts = [
            '/',
            Templex::PLACEHOLDER_OPEN,
            '\s*',
            'if(?<index>' . $index . ')',
            '\s*\(',
            '\s*',
            '(?<variable>.+?)', // select the contents of the brackets, but don't be greedy
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

        $template = preg_replace_callback(
            implode('', $regexParts),
            function (array $matches) use ($variables): string {

                $elseRegex = [
                    '/',
                    Templex::PLACEHOLDER_OPEN,
                    '\s*',
                    'else' . $matches['index'],
                    '\s*',
                    Templex::PLACEHOLDER_CLOSE,
                    '/si',
                ];

                $conditionalBody = preg_split(implode('', $elseRegex), $matches['body']);

                $condition = $this->resolveCondition(trim($matches['variable']), $variables);

                if ($condition) {
                    return trim($conditionalBody[0]);
                }

                return trim($conditionalBody[1] ?? '');
            },
            $template,
        );

        return (new VariableSlot($this->engine))->process($template, $variables);
    }

    /**
     * @throws VariableNotFoundException
     * @throws UnmatchedComparisonException
     */
    protected function resolveCondition(string $condition, Properties $variables): bool
    {
        $simpleComparisonRegex = [
            'self' => '/^\$(?<variable>\w+)\s?$/si',
            'boolean' => '/^(?<boolean>true|false)\s?$/si',
            'comparison' =>
                '/^' .
                '(' .
                '((?<first_is_literal>[\"\'])(?<first_literal_value>[\w]+)(\k<first_is_literal>))|' .
                '(((?<first_is_variable>[\$]){1}|(?<first_is_number>[\d]))' .
                '(?<first_value>\w*))' .
                ')' .
                '((?:\s?)(?<operators>[=!><]+)(?:\s?))' .
                '(' .
                '((?<second_is_literal>[\"\'])(?<second_literal_value>[\w]+)(\k<second_is_literal>))|' .
                '(((?<second_is_variable>[\$]){1}|(?<second_is_number>[\d]))' .
                '(?<second_value>\w*))' .
                ')' .
                '$/si',
        ];

        foreach ($simpleComparisonRegex as $conditionType => $comparisonRegex) {
            if (preg_match($comparisonRegex, $condition, $matches, PREG_UNMATCHED_AS_NULL)) {
                switch ($conditionType) {
                    case 'self':
                        $value = $variables->resolveValue(['variable' => $matches['variable']]);
                        if (!in_array(mb_strtolower($value), ['true', 'false', '0', '1']) && !empty($value)) {
                            return true;
                        }

                        return  boolval((int)filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    case 'boolean':
                        return boolval((int)filter_var($matches['boolean'], FILTER_VALIDATE_BOOLEAN));
                    case 'comparison':
                        return $this->resolveComparison($matches, $variables);
                }
            }
        }

        throw new UnmatchedComparisonException(sprintf('Unable to resolve condition "%s"', $condition));
    }

    protected function resolveComparison($matches, Properties $variables): bool
    {

        $firstIsLiteral = isset($matches['first_is_literal']);
        $firstIsVar = isset($matches['first_is_variable']);
        $firstIsNumber = isset($matches['first_is_number']);

        $secondIsLiteral = isset($matches['second_is_literal']);
        $secondIsVar = isset($matches['second_is_variable']);
        $secondIsNumber = isset($matches['second_is_number']);

        $operation = $matches['operators'];

        if ($firstIsVar) {
            $firstValue = $variables->resolveValue(['variable' => $matches['first_value']]);
        } elseif ($firstIsNumber) {
            $firstValue = ($matches['first_is_number'] . $matches['first_value']) * 1;
        } elseif ($firstIsLiteral) {
            $firstValue = $matches['first_literal_value'];
        }

        if ($secondIsVar) {
            $secondValue = $variables->resolveValue(['variable' => $matches['second_value']]);
        } elseif ($secondIsNumber) {
            $secondValue = ($matches['second_is_number'] . $matches['second_value']) * 1;
        } elseif ($secondIsLiteral) {
            $secondValue = $matches['second_literal_value'];
        }

        return match ($operation) {
            '==' => $firstValue == $secondValue,
            '===' => $firstValue === $secondValue,
            '!=' => $firstValue != $secondValue,
            '!==' => $firstValue !== $secondValue,
            '>' => $firstValue > $secondValue,
            '<' => $firstValue < $secondValue,
            '>=' => $firstValue >= $secondValue,
            '<=' => $firstValue <= $secondValue,
            default => throw new Exception('Unknown comparison operator found ' . $operation),
        };
    }
}
