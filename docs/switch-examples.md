# Switch Statement Examples for Templex

The switch statement feature allows you to create conditional logic based on variable values, similar to switch statements in programming languages.

## Basic Syntax

```php
<{ switch( $variable ) }>
    <{ case( 'value1' ) }>
        Content for value1
    <{ case( 'value2' ) }>
        Content for value2
    <{ default }>
        Default content when no cases match
<{ endswitch }>
```

## Examples

### String Values
```php
<{ switch( $userRole ) }>
    <{ case( 'admin' ) }>
        <p>Administrator Access</p>
    <{ case( 'moderator' ) }>
        <p>Moderator Access</p>
    <{ case( 'user' ) }>
        <p>User Access</p>
    <{ default }>
        <p>Guest Access</p>
<{ endswitch }>
```

### Numeric Values
```php
<{ switch( $priority ) }>
    <{ case( 1 ) }>
        <span class="critical">Critical</span>
    <{ case( 2 ) }>
        <span class="high">High</span>
    <{ case( 3 ) }>
        <span class="medium">Medium</span>
    <{ default }>
        <span class="low">Low</span>
<{ endswitch }>
```

### Boolean Values
```php
<{ switch( $isEnabled ) }>
    <{ case( true ) }>
        <span class="enabled">Feature Enabled</span>
    <{ case( false ) }>
        <span class="disabled">Feature Disabled</span>
<{ endswitch }>
```

### Variable Cases
```php
<{ switch( $currentStatus ) }>
    <{ case( $activeStatus ) }>
        <p>Status is active</p>
    <{ case( $inactiveStatus ) }>
        <p>Status is inactive</p>
    <{ default }>
        <p>Status unknown</p>
<{ endswitch }>
```

### Nested Switch Statements
```php
<{ switch( $category ) }>
    <{ case( 'electronics' ) }>
        <h2>Electronics</h2>
        <{ switch( $subcategory ) }>
            <{ case( 'phones' ) }>
                <p>Mobile Phones</p>
            <{ case( 'laptops' ) }>
                <p>Laptop Computers</p>
            <{ default }>
                <p>Other Electronics</p>
        <{ endswitch }>
    <{ case( 'books' ) }>
        <h2>Books</h2>
        <{ switch( $subcategory ) }>
            <{ case( 'fiction' ) }>
                <p>Fiction Books</p>
            <{ case( 'nonfiction' ) }>
                <p>Non-Fiction Books</p>
            <{ default }>
                <p>Other Books</p>
        <{ endswitch }>
    <{ default }>
        <h2>Other Category</h2>
<{ endswitch }>
```

## Features

- **Strict Comparison**: Uses `===` for case matching
- **Multiple Data Types**: Supports strings, numbers, booleans, and variables
- **Default Case**: Optional fallback when no cases match
- **Nested Support**: Switch statements can be nested within other switch statements
- **Variable Cases**: Case values can be variables, not just literals
- **No Fallthrough**: Each case is independent (no break statements needed)

## Usage with PHP

```php
use Myerscode\Templex\Templex;

$templex = new Templex(__DIR__ . '/templates/');

$data = [
    'userRole' => 'admin',
    'priority' => 1,
    'isEnabled' => true
];

$result = $templex->render('my-template.stub', $data);
echo $result;
```