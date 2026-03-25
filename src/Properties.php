<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\VariableNotFoundException;

readonly class Properties
{
    /**
     * @param array<string, mixed> $variables
     */
    public function __construct(protected array $variables)
    {
    }

    /**
     * @param array<string, string|null> $matches
     *
     * @throws VariableNotFoundException
     */
    public function resolveValue(array $matches): mixed
    {
        $variable = $matches['variable'] ?? '';

        if ($variable === '' || !isset($this->variables[$variable])) {
            throw new VariableNotFoundException($variable . ' not found');
        }

        return $this->variables[$variable];
    }

    /**
     * @return array<string, mixed>
     */
    public function variables(): array
    {
        return $this->variables;
    }
}
