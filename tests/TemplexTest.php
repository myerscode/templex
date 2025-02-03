<?php

namespace Tests;

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


    public function testSetsDefaultExtensions(): void
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/');

        $this->assertEquals(['stub', 'template'], $templex->stubManager()->templateExtensions());
    }



    public function testSanitisesExtensions(): void
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/', '.stub,   template,  .tmpl,stub');

        $this->assertEquals(['stub', 'template', 'tmpl'], $templex->stubManager()->templateExtensions());
    }
}
