<?php

namespace Tests;

use Myerscode\Templex\Stub;
use Myerscode\Templex\Templex;

class TemplexTest extends TestCase
{

    public function testRenderTemplateWithProperties(): void
    {
        $data = [
            'CommandClass' => 'TestCommand',
            'CommandName' => 'test:command',
            'CommandDescription' => 'This is the commands description',
        ];

        $result = $this->render->render('php-parameters.stub', $data);

        $this->assertEquals($this->expectedContent('php-parameters.stub'), $result);
    }

    public function testLoadsTemplates()
    {
        $expected = [
            'partials.header',
            'partials.stub-partial',
            'php-parameters',
        ];

        $templates = $this->render->templates();

        foreach ($expected as $expects) {
            $this->assertArrayHasKey($expects, $templates);
        }
    }

    public function testCachesTemplate()
    {
        $this->assertFalse($this->render->isTemplateCached('text-only'));
        $this->render->cacheTemplates();
        $this->assertTrue($this->render->isTemplateCached('text-only'));
    }

    public function testCachesTemplateIfNotLoaded()
    {
        $this->assertFalse($this->render->isTemplateCached('text-only'));
        $this->render->render('text-only');

        $this->assertTrue($this->render->isTemplateCached('text-only'));
        $this->render->render('text-only');
    }

    public function testIsTemplate()
    {
        $this->assertTrue($this->render->isTemplate('loop'));
        $this->assertTrue($this->render->isTemplate('partials.header'));

        $this->assertFalse($this->render->isTemplate('does-not-exist'));
        $this->assertFalse($this->render->isTemplate('partials.error'));
    }

    public function testTemplateNamesAreLowerCase()
    {
        $this->assertTrue($this->render->isTemplate('loop'));
        $this->assertFalse($this->render->isTemplate('LOOP'));
        $this->assertTrue($this->render->isTemplate('loop'));
        $this->assertFalse($this->render->isTemplate('PARTIALS.HEADER'));
    }

    public function testClearTemplateCache()
    {
        $this->assertTrue(count($this->render->templates()) > 0);
        $this->render->clearTemplateCache();
        $this->assertTrue(count($this->render->templates()) === 0);
    }

    public function testSetsDefaultExtensions()
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/');

        $this->assertEquals(['stub', 'template'], $templex->templateExtensions());
    }

    public function testSetsCustomExtensions()
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/', 'templex');

        $this->assertEquals(['templex'], $templex->templateExtensions());

        $templex->setTemplateExtensions(['foo', 'bar']);

        $this->assertEquals(['foo', 'bar'], $templex->templateExtensions());
    }

    public function testSanitisesExtensions()
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/', '.stub,   template,  .tmpl,stub');

        $this->assertEquals(['stub', 'template', 'tmpl'], $templex->templateExtensions());
    }
}
