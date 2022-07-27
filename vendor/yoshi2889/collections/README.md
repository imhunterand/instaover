# Collections
[![Build Status](https://scrutinizer-ci.com/g/Yoshi2889/collections/badges/build.png)](https://scrutinizer-ci.com/g/Yoshi2889/collections/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yoshi2889/collections/badges/quality-score.png)](https://scrutinizer-ci.com/g/Yoshi2889/collections/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/Yoshi2889/collections/badges/coverage.png)](https://scrutinizer-ci.com/g/Yoshi2889/collections/code-structure/master/code-coverage)
[![Latest Stable Version](https://poser.pugx.org/yoshi2889/collections/v/stable)](https://packagist.org/packages/yoshi2889/collections)
[![Latest Unstable Version](https://poser.pugx.org/yoshi2889/collections/v/unstable)](https://packagist.org/packages/yoshi2889/collections)
[![Total Downloads](https://poser.pugx.org/yoshi2889/collections/downloads)](https://packagist.org/packages/yoshi2889/collections)

Simple Collection class allowing storage of specific data, based on PHP's ArrayObject.

## Installation
You can install this class via `composer`:

```composer require yoshi2889/collections```

## Usage
To use a Collection, create a new instance:

```php
$validationClosure = function ($value)
{
    return is_string($value);
};

$initialItems = ['This is a test value!', 'This is another test value!'];

$collection = new \Yoshi2889\Collections\Collection($validationClosure, $initialItems);
```

Note that the closure passed as the first parameter to the `Collection` constructur **MUST** return a boolean value.
This function is used to validate any added data types. A returned value of true means the given value may be added to the collection.

The second parameter can contain any initial values which should be in the collection. These items will also be validated.

### Validating
Data is validated using a `Closure` instance, or a callback. The given closure must support exactly 1 untyped(!) parameter and
return a boolean value. Values for which the closure returns `false` will be denied with an `\InvalidArgumentException`.

Please note that only the **values** are validated, while keys are not.

Validation can be manually performed on values using the `->validateType($value)` and `->validateArray(array $array)` methods, which
check a single value and an array of values respectively.

`->validateType($value)` will return boolean `true` if the value passes validation, and `false` if it does not.

`->validateArray(array $array)` will return boolean `true` if **all** the values pass validation, and `false` if one or more do not.
 
### Manipulation
A `Collection` instance may be treated like a generic array. This means you can manipulate it like the following example:

```php
$validationClosure = function ($value)
{
    return is_string($value);
};

$collection = new \Yoshi2889\Collections\Collection($validationClosure);

$collection['foo'] = 'bar';
```

Please note that any data entered this way will still be validated and may still throw an `\InvalidArgumentException`.

Furthermore, to remove data from the Collection you may use the `->offsetUnset($index)` and `->removeAll($value)` methods.

`->offsetUnset($index)` will remove the value located at key `$index`, which works similarly to using `unset($collection['key'])`.

`->removeAll($value)` will remove every value which is equal to `$value` from the collection. Because values may exist multiple times 
in the same Collection, it is not possible to reliably remove only a single value from the collection using a value; consider removing by key instead.
If you wish to preserve the original values while creating a new instance without certain values, please refer to Filtering below.

### Filtering
A Collection can be filtered using the `->filter(\Closure $condition)` method. The given closure **MUST** return a boolean value.

The condition closure is called for every element in the collection. Any elements for which the closure returns `true` will be kept
and put in a new `Collection` instance, which will be returned. For example, to filter out all elements which are NOT `foo`:

```php
$validationClosure = function ($value)
{
    return is_string($value);
};

$initialItems = ['foo', 'bar', 'baz', 'foo', 'bar', 'baz'];

$collection = new \Yoshi2889\Collections\Collection($validationClosure, $initialItems);

$filterClosure = function ($value)
{
    return $value != 'foo';
};

// Will contain: ['bar', 'baz', 'bar', 'baz']
$newCollection = $collection->filter($filterClosure);
```

### Events
A Collection will emit a `changed` event if data gets added to or removed from it. No arguments are passed. 
This functionality is borrowed from [Événement](https://github.com/igorw/evenement) by _igorw_.

You can subscribe to the event using the `->on($event, callable $listener)` method, like so:

```php
$collection->on('changed', function ()
{
    echo 'The collection changed!';
});
```

## Validation Closures
You can find a lot of closures and utilities which can be used by a Collection in the separate [validation-closures project](https://github.com/Yoshi2889/validation-closures).

## License
This code is released under the MIT License. Please see `LICENSE` to read it.