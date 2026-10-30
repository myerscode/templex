# Templex

> A lightweight, regex based template rendering engine for PHP

[![Latest Stable Version](https://poser.pugx.org/myerscode/templex/v/stable)](https://packagist.org/packages/myerscode/templex)
[![Total Downloads](https://poser.pugx.org/myerscode/templex/downloads)](https://packagist.org/packages/myerscode/templex)
[![PHP Version Require](http://poser.pugx.org/myerscode/templex/require/php)](https://packagist.org/packages/myerscode/templex)
[![License](https://poser.pugx.org/myerscode/templex/license)](https://github.com/myerscode/templex/blob/main/LICENSE)
[![Tests](https://github.com/myerscode/templex/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/myerscode/templex/actions/workflows/tests.yml)
[![codecov](https://codecov.io/gh/myerscode/templex/graph/badge.svg)](https://codecov.io/gh/myerscode/templex)

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

- [Condition Examples](docs/condition-examples.md)
- [Foreach Examples](docs/foreach-examples.md)
- [For Loop Examples](docs/for-loop-examples.md)
- [Switch Statement Examples](docs/switch-examples.md)

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/templex/issues).

## Contributing

Contributions are welcome! Please see [contributing.md](https://github.com/myerscode/docs/blob/master/contributing.md) for guidelines on how to contribute to this project.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
