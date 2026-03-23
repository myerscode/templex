# Slots

Slots are the core processing units of Templex. Each slot handles a specific type of template directive, performing replacement and hydration to produce the final rendered output.

## Default Slots

Templex includes four built-in slots, processed in this order:

1. **IncludeSlot** — includes another template's content
2. **ControlSlot** — processes control flow (if/else, foreach, for, switch)
3. **TernarySlot** — resolves ternary and null coalescing expressions
4. **VariableSlot** — replaces variable placeholders

## Custom Slots

You can extend Templex with custom slots that implement `SlotInterface`:

```php
$templex->setSlots([
    CustomSlot::class,
    IncludeSlot::class,
    ControlSlot::class,
    VariableSlot::class,
]);
```

---

## Includes

Include another template by its name to create reusable partials:

```text
<{ include partials.header }>
<{ include layouts.base }>
```

Dot notation maps to subdirectories, so `partials.header` resolves to `partials/header.stub`.

---

## Variables

Variables are replaced when matching placeholders are found. They can be passed in at render time, or created by other slots such as loops.

```text
Hello <{ $name }>, welcome to <{ $siteName }>!
```

```php
$templex->render('greeting', [
    'name'     => 'Fred',
    'siteName' => 'Templex',
]);
```

---

## Conditions

If/else statements support variables, numbers, booleans, and literal strings with the usual comparison operators.

```text
<{ if( $role === 'admin' ) }>
    <p>Welcome, administrator.</p>
<{ endif }>
```

With an else branch:

```text
<{ if( $authenticated ) }>
    <p>You are logged in.</p>
<{ else }>
    <p>Please log in.</p>
<{ endif }>
```

### Supported Operators

`==`, `===`, `!=`, `!==`, `>`, `<`, `>=`, `<=`

### Self-Evaluation

A variable on its own evaluates as truthy or falsy:

```text
<{ if( $hasAccess ) }>
    Granted
<{ endif }>
```

### Boolean Literals

```text
<{ if( true ) }>
    Always shown
<{ endif }>
```

Conditions can be nested and combined with other control structures. For more examples including nested conditions and operator usage, see the [Condition Examples](condition-examples.md).

---

## Foreach Loops

Foreach loops iterate over an array variable:

```text
<ul>
    <{ foreach( $users as $user ) }>
        <li><{ $user }></li>
    <{ endforeach }>
</ul>
```

Key-value iteration is also supported using `$key => $value` syntax:

```text
<dl>
    <{ foreach( $settings as $key => $value ) }>
        <dt><{ $key }></dt>
        <dd><{ $value }></dd>
    <{ endforeach }>
</dl>
```

The loop variables are scoped to the loop body and have access to all parent variables. [Loop metadata](#loop-metadata) variables (`$loop_index`, `$loop_first`, `$loop_last`, `$loop_count`) are also available. For more examples including nested loops and key-value patterns, see the [Foreach Examples](foreach-examples.md).

---

## For Loops

For loops use C-style syntax with initialization, condition, and increment:

```text
<{ for( $i = 0; $i < 5; $i++ ) }>
    Item <{ $i }>
<{ endfor }>
```

Variables can be used for dynamic bounds:

```text
<{ for( $i = $start; $i <= $end; $i++ ) }>
    Value: <{ $i }>
<{ endfor }>
```

### Supported Increments

- `$i++` / `$i--`
- `$i += 2` / `$i -= 1`

### Supported Condition Operators

`<`, `<=`, `>`, `>=`, `==`, `===`, `!=`, `!==`

For more examples including nested loops, tables, and pagination patterns, see the [For Loop Examples](for-loop-examples.md).

---

## Loop Metadata

Both `foreach` and `for` loops expose metadata variables inside the loop body:

| Variable | Description |
|---|---|
| `$loop_index` | Zero-based iteration index (0, 1, 2, ...) |
| `$loop_count` | Total number of iterations |
| `$loop_first` | `true` on the first iteration |
| `$loop_last` | `true` on the last iteration |

```text
<{ foreach( $items as $item ) }>
    <{ if( $loop_first ) }>First: <{ endif }><{ $item }>
    <{ if( $loop_last ) }> (last of <{ $loop_count }>)<{ endif }>
<{ endforeach }>
```

These work identically in `for` loops:

```text
<{ for( $i = 0; $i < 5; $i++ ) }>
    <{ $loop_index }>: <{ $i }><{ if( $loop_last ) }> [done]<{ endif }>
<{ endfor }>
```

---

## Switch Statements

Switch statements match a variable against multiple cases:

```text
<{ switch( $status ) }>
    <{ case( "active" ) }>
        Account is active
    <{ case( "suspended" ) }>
        Account is suspended
    <{ default }>
        Unknown status
<{ endswitch }>
```

Cases support strings, numbers, booleans, and variables. Matching uses strict comparison (`===`). There is no fallthrough — each case is independent.

```text
<{ switch( $level ) }>
    <{ case( 1 ) }>
        Beginner
    <{ case( $maxLevel ) }>
        Expert
    <{ default }>
        Intermediate
<{ endswitch }>
```

For more examples including nested switches and variable cases, see the [Switch Statement Examples](switch-examples.md).

---

## Ternary and Null Coalescing

Inline expressions for simple conditional output without full if/else blocks.

### Ternary

Output different values based on a variable's truthiness:

```text
<{ $active ? "Enabled" : "Disabled" }>
<{ $count ? "Has items" : "Empty" }>
```

Truthy: `true`, `1`, non-empty strings (except `"false"` and `"0"`).
Falsy: `false`, `0`, `""`, `"false"`, `"0"`, or missing variables.

### Null Coalescing

Provide a default value when a variable doesn't exist:

```text
<{ $title ?? "Untitled" }>
<{ $theme ?? "light" }>
```

If the variable exists, its value is used. If it doesn't exist, the default is used.

### Values

Both ternary and null coalescing support string literals, numbers, and variable references:

```text
<{ $name ?? $defaultName }>
<{ $role ? $adminLabel : $guestLabel }>
<{ $port ?? 8080 }>
```

Ternary and null coalescing work inside loops and other control structures.
