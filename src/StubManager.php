<?php

namespace Myerscode\Templex;

use Myerscode\Templex\Exceptions\TemplateNotFoundException;
use Myerscode\Templex\Slots\ControlSlot;
use Myerscode\Templex\Slots\IncludeSlot;
use Myerscode\Templex\Slots\VariableSlot;
use Myerscode\Templex\Stub\StubInterface;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as StringService;
use Symfony\Component\Finder\SplFileInfo;

class StubManager
{


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
     * @var StubInterface[]
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
        $extensions = is_string($templateExtensions) ? explode(',', $templateExtensions) : $templateExtensions;

        $extensions = array_filter($extensions, fn($value): bool => is_string($value) && $value !== '');

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
            fn(SplFileInfo $file): Stub => new Stub($this->makeTemplateName($file->getRealPath()), $file->getRealPath()),
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
