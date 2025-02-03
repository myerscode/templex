<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\VariableNotFoundException;

class Properties
{
    public function __construct(protected array $variables)
    {
        //
    }

    /**
     * @throws VariableNotFoundException
     */
    public function resolveValue(array $matches): mixed
    {
        $variable = $matches['variable'] ?? null;

        if (!isset($this->variables[$variable])) {
            throw new VariableNotFoundException($variable . ' not found');
        }

        return $this->variables[$variable];
    }

    public function variables(): array
    {
        return $this->variables;
    }
}
