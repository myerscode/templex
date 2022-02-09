<?php

namespace Tests;

use Myerscode\Templex\Templex;

class StubManagerTest extends TestCase
{
    public function testLoadsTemplates()
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

    public function testCachesTemplate()
    {
        $this->assertFalse($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->cacheTemplates();
        $this->assertTrue($this->stubManager->isTemplateCached('text-only'));
    }

    public function testCachesTemplateIfNotLoaded()
    {
        $this->assertFalse($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->getStub('text-only');

        $this->assertTrue($this->stubManager->isTemplateCached('text-only'));
        $this->stubManager->getStub('text-only');
    }

    public function testIsTemplate()
    {
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertTrue($this->stubManager->isTemplate('partials.header'));

        $this->assertFalse($this->stubManager->isTemplate('does-not-exist'));
        $this->assertFalse($this->stubManager->isTemplate('partials.error'));
    }

    public function testTemplateNamesAreLowerCase()
    {
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertFalse($this->stubManager->isTemplate('LOOP'));
        $this->assertTrue($this->stubManager->isTemplate('loop'));
        $this->assertFalse($this->stubManager->isTemplate('PARTIALS.HEADER'));
    }

    public function testClearTemplateCache()
    {
        $this->assertTrue(count($this->stubManager->templates()) > 0);
        $this->stubManager->clearTemplateCache();
        $this->assertTrue(count($this->stubManager->templates()) === 0);
    }

    public function testSetsCustomExtensions()
    {
        $this->assertEquals(['stub'], $this->stubManager->templateExtensions());

        $this->stubManager->setTemplateExtensions(['foo', 'bar']);

        $this->assertEquals(['foo', 'bar'], $this->stubManager->templateExtensions());
    }

}
