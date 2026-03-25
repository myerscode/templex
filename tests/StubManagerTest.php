<?php

declare(strict_types=1);

namespace Tests;

final class StubManagerTest extends TestCase
{
    public function testLoadsTemplates(): void
    {
        $expected = [
            'partials.header',
            'partials.stub-partial',
            'php-parameters',
        ];

        $templates = $this->stubManager->templates();

        foreach ($expected as $expects) {
            $this->assertArrayHasKey($expects, $templates);
        }
    }

    public function testCachesTemplate(): void
    {
        $this->assertFalse($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->cacheTemplates();
        $this->assertTrue($this->stubManager->isTemplateCached('text-only'));
    }

    public function testCachesTemplateIfNotLoaded(): void
    {
        $this->assertFalse($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->getStub('text-only');

        $this->assertTrue($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->getStub('text-only');
    }

    public function testIsTemplate(): void
    {
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertTrue($this->stubManager->isTemplate('partials.header'));

        $this->assertFalse($this->stubManager->isTemplate('does-not-exist'));
        $this->assertFalse($this->stubManager->isTemplate('partials.error'));
    }

    public function testTemplateNamesAreLowerCase(): void
    {
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertFalse($this->stubManager->isTemplate('LOOP'));
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertFalse($this->stubManager->isTemplate('PARTIALS.HEADER'));
    }

    public function testClearTemplateCache(): void
    {
        $this->assertNotSame([], $this->stubManager->templates());
        $this->stubManager->clearTemplateCache();
        $this->assertSame([], $this->stubManager->templates());
    }

    public function testSetsCustomExtensions(): void
    {
        $this->assertSame(['stub'], $this->stubManager->templateExtensions());

        $this->stubManager->setTemplateExtensions(['foo', 'bar']);

        $this->assertSame(['foo', 'bar'], $this->stubManager->templateExtensions());
    }

    public function testAddsSlot(): void
    {
        $initialCount = count($this->stubManager->slots());
        $this->stubManager->addSlot('App\\CustomSlot');
        $this->assertCount($initialCount + 1, $this->stubManager->slots());
    }
}
