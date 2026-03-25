<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Slots\ControlSlot;
use Myerscode\Templex\Slots\IncludeSlot;
use Myerscode\Templex\Slots\SlotInterface;
use Myerscode\Templex\Slots\TernarySlot;
use Myerscode\Templex\Slots\VariableSlot;
use Myerscode\Templex\Stub\StubInterface;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as StringService;
use Symfony\Component\Finder\SplFileInfo;

class StubManager
{
    /**
     * @var array<string, StubInterface|string>
     */
    protected array $cached = [];
    /** @var array<int, class-string<SlotInterface>> */
    protected array $defaultSlots = [
        IncludeSlot::class,
        ControlSlot::class,
        TernarySlot::class,
        VariableSlot::class,
    ];

    /** @var array<int, class-string<SlotInterface>> */
    protected array $slots = [];

    /**
     * @var string[]
     */
    protected array $templateExtensions = [];


    public function __construct(protected string $templateDirectory, string $templateExtensions = 'stub,template')
    {
        $this->setSlots($this->defaultSlots);

        $this->setTemplateExtensions($templateExtensions);

        $this->fetchTemplates();
    }

    /**
     * @param class-string<SlotInterface> $slots
     */
    public function addSlot(string $slots): void
    {
        $this->slots[] = $slots;
    }

    public function cacheTemplates(): void
    {
        $utility = new FileService($this->templateDirectory);

        $templateList = array_map(
            fn (SplFileInfo $file): Stub => new Stub($this->makeTemplateName($file->getRealPath()), $file->getRealPath()),
            $utility->files(),
        );

        foreach ($templateList as $template) {
            $this->cached[$template->name()] = $template;
        }
    }

    public function clearTemplateCache(): void
    {
        $this->cached = [];
    }

    public function fetchTemplates(): void
    {
        $utility = new FileService($this->templateDirectory);

        foreach ($utility->files() as $file) {
            $this->cached[$this->makeTemplateName($file->getRealPath())] = $file->getRealPath();
        }
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function getStub(string $template): StubInterface
    {
        if (isset($this->cached[$template]) && ($this->cached[$template] instanceof Stub)) {
            return $this->cached[$template];
        }

        if (isset($this->cached[$template]) && is_string($this->cached[$template])) {
            $this->cached[$template] = new Stub($template, $this->cached[$template]);
            return $this->cached[$template];
        }

        $templateFile = $this->templateDirectory . $template;

        $templateName = $this->makeTemplateName($templateFile);

        if (file_exists($templateFile)) {
            $this->cached[$templateName] = new Stub($templateName, $templateFile);

            return $this->cached[$templateName];
        }

        throw new TemplateNotFoundException(sprintf('Template %s not found', $templateName));
    }

    /**
     * @throws TemplateNotFoundException
     */
    public function getTemplate(string $template): string
    {
        return $this->getStub($template)->content();
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

    /**
     * @param array<int, class-string<SlotInterface>> $slots
     */
    public function setSlots(array $slots): void
    {
        $this->slots = $slots;
    }

    /**
     * @param array<int, mixed>|string $templateExtensions
     */
    public function setTemplateExtensions(array|string $templateExtensions): void
    {
        $extensions = is_string($templateExtensions) ? explode(',', $templateExtensions) : $templateExtensions;

        $extensions = array_filter($extensions, fn ($value): bool => is_string($value) && $value !== '');

        $this->templateExtensions = array_unique(array_map(fn ($extension): string => new StringService($extension)->trim(",. \t\n\r\0\x0B")->value(), $extensions));
    }

    /**
     * @return array<int, class-string<SlotInterface>>
     */
    public function slots(): array
    {
        return $this->slots;
    }

    /**
     * @return string[]
     */
    public function templateExtensions(): array
    {
        return $this->templateExtensions;
    }

    /**
     * @return array<string, StubInterface|string>
     */
    public function templates(): array
    {
        return $this->cached;
    }

    protected function makeTemplateName(string $templatePath): string
    {
        $removeFromPath = [
            $this->templateDirectory,
        ];

        $name = new StringService($templatePath);

        foreach ($this->templateExtensions() as $extension) {
            $name = $name->removeFromEnd($extension);
        }

        return $name
            ->replace($removeFromPath, '')
            ->trim(",. \t\n\r\0\x0B")
            ->replace(DIRECTORY_SEPARATOR, '.')
            ->toLowercase()
            ->value();
    }
}
