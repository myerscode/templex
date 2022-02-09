<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Slots\ControlSlot;
use Myerscode\Templex\Slots\IncludeSlot;
use Myerscode\Templex\Slots\SlotInterface;
use Myerscode\Templex\Slots\VariableSlot;
use Myerscode\Templex\Stub\StubInterface;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as StringService;
use SplFileInfo;

class Templex
{
    const PLACEHOLDER_OPEN = '<{';

    const PLACEHOLDER_CLOSE = '}>';

    protected StubManager $stubManager;

    public function __construct(protected string $templateDirectory, string $templateExtensions = 'stub,template')
    {
        $this->stubManager = new StubManager($templateDirectory, $templateExtensions);
    }

    public function compile(StubInterface $template, Properties $properties): string
    {
        return $this->process($template->content(), $properties);
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function process(string $template, Properties $properties): string
    {
        foreach ($this->stubManager->slots() as $slotClass) {
            if (class_exists($slotClass) && is_subclass_of($slotClass, SlotInterface::class, true)) {
                $template = (new $slotClass($this->stubManager))->process($template, $properties);
            }
        }

        return $template;
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function render(string $templateName, array $variables = []): string
    {
        $properties = new Properties($variables);

        $stub = $this->stubManager->getStub($templateName);

        return $this->compile($stub, $properties);
    }

    public function setSlots(array $slots): void
    {
        $this->stubManager->setSlots($slots);
    }

    public function stubManager(): StubManager
    {
        return $this->stubManager;
    }
}
