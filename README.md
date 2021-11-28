# Templex
> A lightweight, regex based template rendering engine for PHP

[![Latest Stable Version](https://poser.pugx.org/myerscode/templex/v/stable)](https://packagist.org/packages/myerscode/templex)
[![Total Downloads](https://poser.pugx.org/myerscode/templex/downloads)](https://packagist.org/packages/myerscode/templex)
[![License](https://poser.pugx.org/myerscode/templex/license)](https://packagist.org/packages/myerscode/templex)
![Tests](https://github.com/myerscode/templex/workflows/Tests/badge.svg?branch=main)

## Why this package is helpful?

This package will allow you to define stubs and then hydrate the template using PHP variables. 
As the engine uses RegEx it does not rely on using `eval` or including and running the code as PHP. 

This means that you are able to simply generate _*new*_ PHP (or any type of text content filled files for that matter) you need.

## Install

You can install this package via composer:

``` bash
composer require myerscode/templex
```

## Usage


```php 
    $templateDirectory = __DIR__ . '/Resources/Templates/;
    $templateExtentions = 'stub';
    $templex = new Templex($templateDirectory, $templateExtentions);
    
    echo $templex->render('index');
```

### Template Directory

## Templates

Templates can be any form of text based files. By default, Templex will look for files with `.stub` or `.template` file extensions.

Templex uses `<{ }>` as anchor tags to find and process `Slots`

Slots are resolved in the following order of precedence:

* IncludeSlot
* ConditionSlot
* LoopSlot
* VariableSlot

## Slots

Slots are the _magic_ of Templex. They are the isolated functionality that perform replacement and hydrating actions on a template 
to create the final rendered output.


### Includes

To include another template, in order to create reusable stubs you can simply include it by its template name.

```text 
<{ include partials.header }>
```

### Conditions
Conditions can be variables or literal strings
```text
<{ if( $value === $condition ) }>
 <p>That value was true!</p>
<{ endif }>

<{ if( $value === 'foobar' ) }>
 <p>That value was true!</p>
<{ else }>
 <p>That value was false!</p>
<{ endif }>
```

### Loops
Loops will take an array variable to create multiple iterations in your template.
```text
<ul class="row">
    <{ foreach( $users as $user ) }>
        <li><{ $user }></li>
    <{ endforeach }>
</ul>
```

### Variables
Variables are replaced matching anchors are found. They can are passed in at rendering, or created via other slots such as Loops.
```text
Hi <{ $name }>!
```

## Issues and Contributing

We are very happy to receive pull requests to add functionality or fixes.

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/config/issues).

Please read the Myerscode [contributing](https://github.com/myerscode/docs/blob/main/CONTRIBUTING.md) guide for information on our Code of Conduct.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
