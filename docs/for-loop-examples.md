# For Loop Examples for Templex

The for loop feature allows you to create iterative logic with initialization, condition, and increment expressions, similar to for loops in programming languages.

## Basic Syntax

```php
<{ for( $variable = start; condition; increment ) }>
    Content to repeat
<{ endfor }>
```

## Examples

### Basic Counting Loop
```php
<{ for( $i = 0; $i < 5; $i++ ) }>
    <p>Iteration <{ $i }></p>
<{ endfor }>
```

### Countdown Loop
```php
<{ for( $i = 10; $i > 0; $i-- ) }>
    <p>Countdown: <{ $i }></p>
<{ endfor }>
```

### Step Increment
```php
<{ for( $i = 0; $i < 20; $i += 5 ) }>
    <p>Value: <{ $i }></p>
<{ endfor }>
```

### Step Decrement
```php
<{ for( $i = 100; $i > 0; $i -= 10 ) }>
    <p>Countdown by 10: <{ $i }></p>
<{ endfor }>
```

### Using Variables for Limits
```php
<{ for( $i = $startValue; $i <= $endValue; $i++ ) }>
    <p>Number: <{ $i }></p>
<{ endfor }>
```

### Different Comparison Operators
```php
<!-- Less than or equal -->
<{ for( $i = 1; $i <= 3; $i++ ) }>
    <p>Item <{ $i }></p>
<{ endfor }>

<!-- Greater than or equal -->
<{ for( $i = 5; $i >= 1; $i-- ) }>
    <p>Reverse: <{ $i }></p>
<{ endfor }>
```

### Nested For Loops
```php
<{ for( $row = 1; $row <= 3; $row++ ) }>
    <div class="row">
        <{ for( $col = 1; $col <= 3; $col++ ) }>
            <span class="cell">(<{ $row }>,<{ $col }>)</span>
        <{ endfor }>
    </div>
<{ endfor }>
```

### Creating Tables
```php
<table>
    <{ for( $row = 1; $row <= $rows; $row++ ) }>
        <tr>
            <{ for( $col = 1; $col <= $cols; $col++ ) }>
                <td>Cell <{ $row }>-<{ $col }></td>
            <{ endfor }>
        </tr>
    <{ endfor }>
</table>
```

### Generating Lists
```php
<ul>
    <{ for( $i = 1; $i <= $itemCount; $i++ ) }>
        <li>List item number <{ $i }></li>
    <{ endfor }>
</ul>
```

## Supported Features

### Initialization Patterns
- **Simple assignment**: `$i = 0`
- **Variable assignment**: `$i = $startValue`
- **Any variable name**: `$counter = 1`, `$index = 0`

### Condition Operators
- **Less than**: `$i < 10`
- **Less than or equal**: `$i <= 10`
- **Greater than**: `$i > 0`
- **Greater than or equal**: `$i >= 0`
- **Equal**: `$i == 5`
- **Strict equal**: `$i === 5`
- **Not equal**: `$i != 5`
- **Strict not equal**: `$i !== 5`

### Increment Types
- **Post-increment**: `$i++` (adds 1)
- **Post-decrement**: `$i--` (subtracts 1)
- **Addition assignment**: `$i += 2` (adds specified value)
- **Subtraction assignment**: `$i -= 2` (subtracts specified value)

### Data Types
- **Integers**: `$i = 0`, `$i < 10`
- **Variables**: `$i = $start`, `$i <= $end`
- **Expressions**: Any valid numeric expression

## Advanced Examples

### Creating Pagination
```php
<div class="pagination">
    <{ for( $page = 1; $page <= $totalPages; $page++ ) }>
        <{ if( $page === $currentPage ) }>
            <span class="current"><{ $page }></span>
        <{ else }>
            <a href="?page=<{ $page }>"><{ $page }></a>
        <{ endif }>
    <{ endfor }>
</div>
```

### Building Navigation Menus
```php
<nav>
    <{ for( $level = 1; $level <= $maxLevel; $level++ ) }>
        <ul class="level-<{ $level }>">
            <{ for( $item = 1; $item <= $itemsPerLevel; $item++ ) }>
                <li><a href="/level<{ $level }>/item<{ $item }>">Item <{ $item }></a></li>
            <{ endfor }>
        </ul>
    <{ endfor }>
</nav>
```

## Usage with PHP

```php
use Myerscode\Templex\Templex;

$templex = new Templex(__DIR__ . '/templates/');

$data = [
    'startValue' => 1,
    'endValue' => 5,
    'rows' => 3,
    'cols' => 4,
    'itemCount' => 10
];

$result = $templex->render('my-template.stub', $data);
echo $result;
```

## Performance Notes

- For loops are processed at template compilation time
- Large loops (1000+ iterations) may impact performance
- Consider using `foreach` loops for array iteration instead
- Nested loops multiply iteration counts (3x3 = 9 iterations)

## Best Practices

1. **Use meaningful variable names**: `$row`, `$item`, `$index` instead of `$i`
2. **Avoid infinite loops**: Always ensure the condition will eventually be false
3. **Consider alternatives**: Use `foreach` for arrays, `if` for simple conditions
4. **Limit nesting**: Deep nesting can make templates hard to read
5. **Use variables for limits**: Makes templates more flexible and reusable