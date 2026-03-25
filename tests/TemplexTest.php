<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Templex\Templex;

final class TemplexTest extends TestCase
{
    public function testRenderTemplateWithProperties(): void
    {
        $data = [
            'CommandClass' => 'TestCommand',
            'CommandName' => 'test:command',
            'CommandDescription' => 'This is the commands description',
        ];

        $result = $this->render->render('php-parameters.stub', $data);

        $this->assertSame($this->expectedContent('php-parameters.stub'), $result);
    }



    public function testSanitisesExtensions(): void
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/', '.stub,   template,  .tmpl,stub');

        $this->assertSame(['stub', 'template', 'tmpl'], $templex->stubManager()->templateExtensions());
    }


    public function testSetsDefaultExtensions(): void
    {
        $templex = new Templex(__DIR__ . '/Resources/Templates/');

        $this->assertSame(['stub', 'template'], $templex->stubManager()->templateExtensions());
    }
}
