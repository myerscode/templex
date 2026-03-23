<?php

namespace Myerscode\Templex\Slots;

use Myerscode\Templex\Exceptions\VariableNotFoundException;
use Myerscode\Templex\Properties;
use Myerscode\Templex\Templex;

class TernarySlot extends Slot
{
    public function process(string $template, Properties $variables): string
    {
        $template = $this->resolveNullCoalescing($template, $variables);

        return $this->resolveTernary($template, $variables);
    }

    protected function resolveNullCoalescing(string $template, Properties $variables): string
    {
        $regex = '/' .
            Templex::PLACEHOLDER_OPEN .
            '\s*\$(?<variable>\w+)\s*\?\?\s*(?<default>.+?)\s*' .
            Templex::PLACEHOLDER_CLOSE .
            '/i';

        return (string) preg_replace_callback(
            $regex,
            function (array $matches) use ($variables): string {
                try {
                    $value = $variables->resolveValue(['variable' => $matches['variable']]);

                    return (string) $value;
                } catch (VariableNotFoundException) {
                    return $this->resolveLiteral(trim($matches['default']), $variables);
                }
            },
            $template,
        );
    }

    protected function resolveTernary(string $template, Properties $variables): string
    {
        $regex = '/' .
            Templex::PLACEHOLDER_OPEN .
            '\s*\$(?<variable>\w+)\s*\?\s*(?<truthy>.+?)\s*:\s*(?<falsy>.+?)\s*' .
            Templex::PLACEHOLDER_CLOSE .
            '/i';

        return (string) preg_replace_callback(
            $regex,
            function (array $matches) use ($variables): string {
                try {
                    $value = $variables->resolveValue(['variable' => $matches['variable']]);
                } catch (VariableNotFoundException) {
                    $value = false;
                }

                $isTruthy = $this->evaluateTruthiness($value);

                return $isTruthy
                    ? $this->resolveLiteral(trim($matches['truthy']), $variables)
                    : $this->resolveLiteral(trim($matches['falsy']), $variables);
            },
            $template,
        );
    }

    protected function evaluateTruthiness(mixed $value): bool
    {
        if (is_string($value)) {
            if (in_array(mb_strtolower($value), ['false', '0'], true)) {
                return false;
            }

            return $value !== '';
        }

        return (bool) $value;
    }

    protected function resolveLiteral(string $literal, Properties $variables): string
    {
        // Variable reference
        if (preg_match('/^\$(\w+)$/', $literal, $matches)) {
            try {
                return (string) $variables->resolveValue(['variable' => $matches[1]]);
            } catch (VariableNotFoundException) {
                return '';
            }
        }

        // String literal (single or double quotes)
        if (preg_match('/^["\'](.*)["\']\s*$/', $literal, $matches)) {
            return $matches[1];
        }

        // Numeric or bare value
        return $literal;
    }
}
