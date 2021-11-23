<?php

namespace Tests;

use Myerscode\Templex\Templex;
use Myerscode\Utilities\Files\Utility;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected Templex $render;

    public function setUp(): void
    {
        $this->render = new Templex(__DIR__ . '/Resources/Templates/', '.stub');
    }

    public function templateContent(string $template): string
    {
        return (new Utility(__DIR__ . '/Resources/Templates/' .$template))->content();
    }

    public function expectedContent(string $template): string
    {
        return (new Utility(__DIR__ . '/Resources/Expectations/' .$template))->content();
    }
}
