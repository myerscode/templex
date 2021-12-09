<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Slots\ControlSlot;
use Myerscode\Templex\Slots\IncludeSlot;
use Myerscode\Templex\Slots\SlotInterface;
use Myerscode\Templex\Slots\VariableSlot;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as StringService;
use SplFileInfo;

class Templex
{
    const PLACEHOLDER_OPEN = '<{';

    const PLACEHOLDER_CLOSE = '}>';

    protected array $defaultSlots = [
            IncludeSlot::class,
            ControlSlot::class,
            VariableSlot::class,
        ];

    /**
     * @var string[]
     */
    protected array $templateExtensions = [];

    protected array $slots = [];

    /**
     * @var Stub[]
     */
    protected array $cached = [];

    protected array $templates = [];


    public function __construct(protected string $templateDirectory, string $templateExtensions = 'stub,template')
    {
        $this->setSlots($this->defaultSlots);

        $this->setTemplateExtensions($templateExtensions);

        $this->fetchTemplates();
    }

    public function setTemplateExtensions(array|string $templateExtensions): void
    {
        if (is_string($templateExtensions)) {
            $extensions = explode(',', $templateExtensions);
        } else {
            $extensions = $templateExtensions;
        }

        $extensions = array_filter($extensions, fn($value) => is_string($value) && $value !== '');

        $this->templateExtensions = array_unique(array_map(fn($extension) => (new StringService($extension))->trim(",. \t\n\r\0\x0B")->value(), $extensions));
    }

    public function fetchTemplates(): void
    {
        $fileService = new FileService($this->templateDirectory);

        foreach ($fileService->files() as $file) {
            $this->cached[$this->makeTemplateName($file->getRealPath())] = $file->getRealPath();
        }
    }

    public function cacheTemplates(): void
    {
        $fileService = new FileService($this->templateDirectory);

        $templateList = array_map(
            fn(SplFileInfo $file) => new Stub($this->makeTemplateName($file->getRealPath()), $file->getRealPath()),
            $fileService->files()
        );

        foreach ($templateList as $template) {
            $this->cached[$template->name()] = $template;
        }
    }

    public function clearTemplateCache(): void
    {
        unset($this->cached);
        $this->cached = [];
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function getTemplate(string $template): string
    {
        return $this->getStub($template)->content();
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function getStub(string $template): Stub
    {
        if (isset($this->cached[$template]) && ($this->cached[$template] instanceof Stub)) {
            return $this->cached[$template];
        } elseif (isset($this->cached[$template]) && is_string($this->cached[$template])) {
            $this->cached[$template] = new Stub($template, $this->cached[$template]);

            return $this->cached[$template];
        }

        $templateFile = $this->templateDirectory . $template;

        $templateName = $this->makeTemplateName($templateFile);

        if (file_exists($templateFile)) {
            $this->cached[$templateName] = new Stub($templateName, $templateFile);

            return $this->cached[$templateName];
        }

        throw new TemplateNotFoundException("Template $templateName not found");
    }

    public function isTemplate(string $template): bool
    {
        try {
            $this->getTemplate($template);

            return true;
        } catch (TemplateNotFoundException) {
            return false;
        }
    }

    public function isTemplateCached(string $template): bool
    {
        return isset($this->cached[$template]) && ($this->cached[$template] instanceof Stub);
    }

    protected function makeTemplateName(string $templatePath): string
    {
        $removeFromPath = [
            $this->templateDirectory,
            ...$this->templateExtensions,
        ];

        return (new StringService($templatePath))
            ->replace($removeFromPath, '')
            ->trim(",. \t\n\r\0\x0B")
            ->replace(DIRECTORY_SEPARATOR, '.')
            ->toLowercase()
            ->value();
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function render(string $templateName, array $variables = []): string
    {
        $properties = new Properties($variables);

        $stub = $this->getStub($templateName);

        return $this->compile($stub, $properties);
    }

    public function compile(Stub $template, Properties $properties): string
    {
        return $this->process($template->content(), $properties);
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function process(string $template, Properties $properties): string
    {
        foreach ($this->slots() as $slotClass) {
            if (class_exists($slotClass) && is_subclass_of($slotClass, SlotInterface::class, true)) {
                $template = (new $slotClass($this))->process($template, $properties);
            }
        }

        return $template;
    }


    public function slots(): array
    {
        return $this->slots;
    }

    /**
     * @return Stub[]
     */
    public function templates(): array
    {
        return $this->cached;
    }

    /**
     * @return string[]
     */
    public function templateExtensions(): array
    {
        return $this->templateExtensions;
    }

    public function setSlots(array $slots): void
    {
        $this->slots = $slots;
    }

    public function addSlot(string $slots): void
    {
        $this->slots[] = $slots;
    }
}
