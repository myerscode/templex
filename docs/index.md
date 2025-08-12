# Templex Documentation

Templex is a lightweight, regex-based template rendering engine for PHP that allows you to create dynamic content using simple template syntax.

## Table of Contents

### Getting Started
- [Main README](../README.md) - Installation, basic usage, and overview
- [Switch Examples](switch-examples.md) - Complete guide to using switch statements

### Core Concepts

#### Template Syntax
Templex uses `<{` and `}>` as delimiters for all template directives:

- **Variables**: `<{ $variableName }>`
- **Includes**: `<{ include template.name }>`
- **Conditions**: `<{ if( condition ) }>....<{ endif }>`
- **Loops**: `<{ foreach( $array as $item ) }>....<{ endforeach }>`
- **Switch Statements**: `<{ switch( $variable ) }>....<{ endswitch }>`

#### Slots System
Templex processes templates through a slot system:

1. **IncludeSlot** - Handles template inclusion
2. **ControlSlot** - Processes control flow (if/else, loops, switch)
3. **VariableSlot** - Replaces variable placeholders

### Features

#### Control Structures
- **Conditional Logic**: if/else statements with comparison operators
- **Loops**: foreach loops with nested support
- **Switch Statements**: Multi-case conditional logic with default fallback

#### Template Management
- **File Extensions**: Supports `.stub` and `.template` files by default
- **Template Caching**: Automatic caching for improved performance
- **Nested Templates**: Include other templates for reusability

#### Safety & Performance
- **No Code Execution**: Uses regex processing, not `eval()`
- **Regex-Based**: Fast pattern matching for template processing
- **Extensible**: Add custom slots for additional functionality

### Advanced Usage

#### Custom Slots
Extend Templex by creating custom slots that implement `SlotInterface`:

```php
$templex->setSlots([
    CustomSlot::class,
    IncludeSlot::class,
    ControlSlot::class,
    VariableSlot::class,
]);
```

#### Template Extensions
Configure custom file extensions:

```php
$templex = new Templex($templateDir, 'stub,template,tpl');
```

### Examples & Guides

- [Switch Examples](switch-examples.md) - Comprehensive switch statement usage guide

---

For more information, see the [main README](../README.md) or explore the source code examples in the `tests/Resources/Templates/` directory.