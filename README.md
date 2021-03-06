# Debug functions for PHP

![CI](https://github.com/fabacino/php-debug-functions/workflows/CI/badge.svg)
[![codecov](https://codecov.io/gh/fabacino/php-debug-functions/branch/master/graph/badge.svg)](https://codecov.io/gh/fabacino/php-debug-functions)

A collection of simple debug functions which might be helpful in case a full-fledged debugger is not available.

## Requirements

The following versions of PHP are supported:

* PHP 7.2
* PHP 7.3
* PHP 7.4

## Installation

```
composer install --dev fabacino/debug-functions
```

## Usage

* `dbg`: Print out debug value.
* `dbgr`: Return debug value.
* `dbglog`: Log debug value into file.
* `dbginit`: Initialize debug settings.

The functions accept any value for debugging, as long as it can be passed as a variable.

## Examples

### Print values to standard output

```php
dbg(123);
dbg('a string');
dbg([
    'token' => 'dp83kspo',
    'is_default' => true
]);
```

```
// Output
123
a string
Array
(
    [token] => dp83kspo
    [is_default] => true
)
```

When running in a non-CLI environment the ouput is wrapped around `pre` tags for better formatting.

Use `dbginit` or the flag `USE_VARDUMP` in case you prefer `var_dump`:

```php
dbginit(['use_vardump' => true]);
dbg(123);
// or
dbg(123, Debug::USE_VARDUMP);
// which is the same as
dbg(123, 1);
```

```
// Output
int(123)
string(8) "a string"
array(2) {
  'token' => string(8) "dp83kspo"
  'is_default' => bool(true)
}
```

Use `dbginit` or the flag `USE_HTMLENTITIES` in case you want to encode HTML entities:

```php
dbginit(['use_htmlentities' => true]);
dbg('<b>important</b>');
// or
dbg('<b>important</b>', Debug::USE_HTMLENTITIES);
// which is the same as
dbg('<b>important</b>', 2);
```

```
// Output
&lt;b&gt;important&lt;/b&gt;
```

### Save debug output in variable

```php
// dbgr is the same as dbg, except that the output is returned instead of printed.
$dbg = dbgr(123);
```

### Log debug output to file

```php
// dbglog is the same as dbg, except that the output is logged instead of printed.
dbginit(['log_file' => '/path/to/logfile']);
dbglog($this->doSomething());
```
 
Alternatively you can pass your own PSR-3 compatible logger:
 
```php
$logger = new YourLogger();
...
dbginit(['logger' => $logger]);
dbglog($this->doSomething());
```

## License

The MIT License (MIT). Please see [License File](https://github.com/fabacino/php-debug-functions/blob/master/LICENSE) for more information.
