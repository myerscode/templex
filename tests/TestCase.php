<?php

namespace Tests;

use Myerscode\Templex\RawFileStub;
use Myerscode\Templex\Templex;
use Myerscode\Utilities\Files\Utility;
use Myerscode\Utilities\Strings\Utility as StringUtility;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected Templex $render;

    public function text($text): StringUtility
    {
        return new StringUtility($text);
    }

    public function rawStub(string $text): RawFileStub
    {
        return new RawFileStub('raw', $text);
    }

    public function setUp(): void
    {
        $this->render = new Templex($this->templateDirectory(), '.stub');
    }

    public function templateDirectory(): string
    {
        return (new StringUtility(__DIR__ . '/Resources/Templates/'))->replace(['/'], DIRECTORY_SEPARATOR)->value();
    }

    public function templateContent(string $template): string
    {
        return (new Utility($this->templateDirectory() . $template))->content();
    }

    public function expectedContent(string $template): string
    {
        return (new Utility($this->expectedContentDirectory() . $template))->content();
    }

    public function expectedContentDirectory(): string
    {
        return (new StringUtility(__DIR__ . '/Resources/Expectations/'))->replace(['/'], DIRECTORY_SEPARATOR)->value();
    }
}
