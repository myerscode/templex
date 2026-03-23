# Condition Examples for Templex

The if/else feature allows you to conditionally render content based on variable values, comparisons, and boolean logic.

## Basic Syntax

```text
<{ if( condition ) }>
    Content when true
<{ endif }>

<{ if( condition ) }>
    Content when true
<{ else }>
    Content when false
<{ endif }>
```

## Examples

### Variable Self-Evaluation

A variable on its own evaluates as truthy or falsy:

```text
<{ if( $isLoggedIn ) }>
    <p>Welcome back!</p>
<{ endif }>
```

Truthy values: `true`, `1`, `"true"`, any non-empty string or non-zero number.

Falsy values: `false`, `0`, `"false"`.

### Boolean Literals

```text
<{ if( true ) }>
    This is always shown
<{ endif }>

<{ if( false ) }>
    This is never shown
<{ endif }>
```

### String Comparison

```text
<{ if( $name === 'Fred' ) }>
    <p>Hello Fred!</p>
<{ else }>
    <p>Hello stranger!</p>
<{ endif }>
```

### Numeric Comparison

```text
<{ if( $age >= 18 ) }>
    <p>Adult content available</p>
<{ endif }>

<{ if( $score > 100 ) }>
    <p>High score!</p>
<{ else }>
    <p>Keep trying</p>
<{ endif }>
```

### Variable to Variable Comparison

```text
<{ if( $userRole === $requiredRole ) }>
    <p>Access granted</p>
<{ else }>
    <p>Access denied</p>
<{ endif }>
```

### Not Equal

```text
<{ if( $status != 'disabled' ) }>
    <p>Feature is available</p>
<{ endif }>

<{ if( $count !== 0 ) }>
    <p>Items found: <{ $count }></p>
<{ endif }>
```

### Nested Conditions

Conditions can be nested within other control structures:

```text
<{ foreach( $users as $user ) }>
    <{ if( $user === 'admin' ) }>
        <li class="admin"><{ $user }> (Admin)</li>
    <{ else }>
        <li><{ $user }></li>
    <{ endif }>
<{ endforeach }>
```

### Conditions Inside For Loops

```text
<{ for( $i = 1; $i <= 10; $i++ ) }>
    <{ if( $i === $currentPage ) }>
        <span class="active"><{ $i }></span>
    <{ else }>
        <a href="?page=<{ $i }>"><{ $i }></a>
    <{ endif }>
<{ endfor }>
```

## Supported Operators

| Operator | Description |
|----------|-------------|
| `==`     | Equal (loose) |
| `===`    | Equal (strict) |
| `!=`     | Not equal (loose) |
| `!==`    | Not equal (strict) |
| `>`      | Greater than |
| `<`      | Less than |
| `>=`     | Greater than or equal |
| `<=`     | Less than or equal |

## Supported Value Types

- **Variables**: `$name`, `$count`
- **String literals**: `'hello'`, `"world"`
- **Numbers**: `7`, `49`
- **Booleans**: `true`, `false`

## Usage with PHP

```php
use Myerscode\Templex\Templex;

$templex = new Templex(__DIR__ . '/templates/');

$data = [
    'isLoggedIn'   => true,
    'name'         => 'Fred',
    'role'         => 'admin',
    'score'        => 150,
    'currentPage'  => 3,
];

$result = $templex->render('my-template.stub', $data);
echo $result;
```
