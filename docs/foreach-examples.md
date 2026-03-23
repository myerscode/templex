# Foreach Loop Examples for Templex

The foreach loop iterates over array variables, with optional key access using `$key => $value` syntax.

## Basic Syntax

```text
<{ foreach( $array as $value ) }>
    Content using <{ $value }>
<{ endforeach }>

<{ foreach( $array as $key => $value ) }>
    Content using <{ $key }> and <{ $value }>
<{ endforeach }>
```

## Examples

### Simple Value Iteration

```text
<ul>
    <{ foreach( $users as $user ) }>
        <li><{ $user }></li>
    <{ endforeach }>
</ul>
```

### Key-Value Iteration

```text
<dl>
    <{ foreach( $settings as $key => $value ) }>
        <dt><{ $key }></dt>
        <dd><{ $value }></dd>
    <{ endforeach }>
</dl>
```

### Associative Array with String Keys

```text
<table>
    <{ foreach( $users as $id => $name ) }>
        <tr>
            <td><{ $id }></td>
            <td><{ $name }></td>
        </tr>
    <{ endforeach }>
</table>
```

### Numeric Index Access

For sequential arrays, the key is the numeric index:

```text
<ol start="0">
    <{ foreach( $items as $index => $item ) }>
        <li value="<{ $index }>"><{ $item }></li>
    <{ endforeach }>
</ol>
```

### Key-Value with Conditions

```text
<{ foreach( $roles as $user => $role ) }>
    <{ if( $role === "admin" ) }>
        <strong><{ $user }></strong> (Admin)
    <{ else }>
        <{ $user }> (<{ $role }>)
    <{ endif }>
<{ endforeach }>
```

### Nested Foreach with Keys

```text
<{ foreach( $departments as $dept => $members ) }>
    <h3><{ $dept }></h3>
    <ul>
        <{ foreach( $members as $member ) }>
            <li><{ $member }></li>
        <{ endforeach }>
    </ul>
<{ endforeach }>
```

### Building Config Output

```text
<{ foreach( $config as $key => $value ) }>
<{ $key }>=<{ $value }>
<{ endforeach }>
```

### Generating HTML Attributes

```text
<div
    <{ foreach( $attributes as $attr => $val ) }>
        <{ $attr }>="<{ $val }>"
    <{ endforeach }>
>
    Content
</div>
```

## Usage with PHP

```php
use Myerscode\Templex\Templex;

$templex = new Templex(__DIR__ . '/templates/');

$data = [
    'users'    => ['admin' => 'Fred', 'mod' => 'Chris', 'user' => 'Tor'],
    'settings' => ['theme' => 'dark', 'lang' => 'en'],
    'items'    => ['apple', 'banana', 'cherry'],
];

$result = $templex->render('my-template.stub', $data);
echo $result;
```

## Notes

- When no key is specified (`$array as $value`), only the value is available in the loop body
- When a key is specified (`$array as $key => $value`), both key and value are available
- Loop variables are scoped to the loop body and override any parent variables with the same name
- Nested foreach loops each have their own scope
- Both sequential and associative arrays are supported
