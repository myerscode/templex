# Templex
> A lightweight, regex based template rendering engine for PHP

[![Latest Stable Version](https://poser.pugx.org/myerscode/templex/v/stable)](https://packagist.org/packages/myerscode/templex)
[![Total Downloads](https://poser.pugx.org/myerscode/templex/downloads)](https://packagist.org/packages/myerscode/templex)
[![License](https://poser.pugx.org/myerscode/templex/license)](https://packagist.org/packages/myerscode/templex)
![Tests](https://github.com/myerscode/templex/actions/workflows/tests.yml/badge.svg?branch=main)
[![codecov](https://codecov.io/gh/myerscode/templex/graph/badge.svg?token=YR0YHVERNV)](https://codecov.io/gh/myerscode/templex)

## Why this package is helpful?

Templex lets you define stubs and hydrate them using PHP variables. The engine uses regex for all processing — no `eval()`, no including and running code as PHP. This means you can safely generate new PHP files, config files, or any text-based content from templates.

## Requirements

- PHP 8.5 or higher

## Install

```bash
composer require myerscode/templex
```

## Quick Start

```php
use Myerscode\Templex\Templex;

$templex = new Templex(__DIR__ . '/templates/', 'stub');

echo $templex->render('welcome', ['name' => 'Fred']);
```

Where `templates/welcome.stub` contains:

```text
Hello <{ $name }>!
```

## Documentation

- [Templates](docs/templates.md) — template syntax, file types, and configuration
- [Slots](docs/slots.md) — includes, conditions, loops, switch statements, and variables

### Example Guides

- [For Loop Examples](docs/for-loop-examples.md)
- [Switch Statement Examples](docs/switch-examples.md)

## Issues and Contributing

We are very happy to receive pull requests to add functionality or fixes.

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/templex/issues).

Please read the Myerscode [contributing](https://github.com/myerscode/docs/blob/main/CONTRIBUTING.md) guide for information on our Code of Conduct.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
