# Templates

Templates can be any form of text based file. By default, Templex looks for files with `.stub` or `.template` extensions.

## Syntax

Templex uses `<{` and `}>` as opening and closing delimiters for all template directives:

```text
<{ $variable }>
<{ include template.name }>
<{ if( condition ) }>...<{ endif }>
<{ foreach( $array as $item ) }>...<{ endforeach }>
<{ for( $i = 0; $i < 10; $i++ ) }>...<{ endfor }>
<{ switch( $variable ) }>...<{ endswitch }>
```

## File Extensions

Configure which file extensions Templex should look for:

```php
// Single extension
$templex = new Templex($templateDir, 'stub');

// Multiple extensions (comma-separated)
$templex = new Templex($templateDir, 'stub,template,tpl');
```

## Template Directory

All templates are loaded from a base directory. Subdirectories can be referenced using dot notation:

```php
$templex = new Templex(__DIR__ . '/templates/');

// Renders templates/welcome.stub
$templex->render('welcome', ['name' => 'Fred']);

// Renders templates/emails/invite.stub
$templex->render('emails.invite', ['email' => $email]);
```

## Rendering

Pass variables as an associative array when rendering:

```php
$data = [
    'title'  => 'My Page',
    'users'  => ['Fred', 'Chris', 'Tor'],
    'active' => true,
];

$output = $templex->render('page', $data);
```

## Caching

Templex automatically caches loaded templates for improved performance. The cache can be cleared when needed:

```php
$templex->clearTemplateCache();
```

## Safety

Because Templex uses regex-based pattern matching rather than `eval()` or PHP includes, templates cannot execute arbitrary code. This makes it safe to use with user-provided template content.
