named-sprintf
=============

[![Build Status](https://travis-ci.org/lightster/named-sprintf.svg?branch=master)](https://travis-ci.org/lightster/named-sprintf)
[![Test Coverage](https://codeclimate.com/github/lightster/named-sprintf/badges/coverage.svg)](https://codeclimate.com/github/lightster/named-sprintf/coverage)
[![Code Climate](https://codeclimate.com/github/lightster/named-sprintf/badges/gpa.svg)](https://codeclimate.com/github/lightster/named-sprintf)

Enhance PHP sprintf with Python-style named parameters

## Requirements

 - PHP >= 7.0
 - Composer

## Installation

```bash
composer require lightster/named-sprintf:dev-master
```

## Basic Usage

```php
<?php

use Lstr\Sprintf\Sprintf;

require_once __DIR__ . '/vendor/autoload.php';

$sprintf = new Sprintf();

echo $sprintf->sprintf(
    "Hello %(first_name)s %(last_name)s\n",
    ['first_name' => 'Matt', 'last_name' => 'Light']
);
?>
```

## Type Usage

Similar to PHP's built-in `sprintf`, types and format options can be
passed after the named parameter:

```php
<?php

$sprintf = new Sprintf();

echo $sprintf->sprintf(
    "PI is approximately %(pi).5f, or %(pi).8f if you need more accuracy\n",
    ['pi' => pi()]
);

echo $sprintf->sprintf(
    "The type is optional and defaults to string (e.g. 's'): %(name)\n",
    ['name' => 'Typeless!']
);
?>
```

## Middleware

Values can be processed before they are formatted by passing middleware
to the constructor of `Sprintf`.  The middleware can be any sort of
PHP callable and will be passed the parameter name that is about to be
formatted and a callable that gives the middleware access to all of
the values passed to `$sprintf->sprintf()`.

The below example takes any parameter passed in as an array and converts
it to a space-delimited string of words before passing the value to the
`sprintf` string formatter:

```php
<?php
$sprintf = new Sprintf(
    function ($name, callable $values) {
        $value = $values($name);

        if (is_array($value)) {
            return implode(' ', $value);
        }

        return $value;
    }
);

echo $sprintf->sprintf(
    "Middleware %(action_words) to pre-process %(what)!\n",
    [
        'action_words' => ['can', 'be', 'used'],
        'what'         => 'parameters',
    ]
);
?>
```

##  Reusable Middleware

Reusable, chainable middleware can be developed by extending the
AbstractInvokable class.  Some reusable middleware is shipped with
named-sprintf.

### Cli\Bundle Middleware

The Cli\Bundle middleware is a series of middleware that is bundled
together to allow for easy command line string generation.

```php
<?php

use Lstr\Sprintf\Middleware\Cli\Bundle as CliBundle;

$sprintf = new Sprintf(new CliBundle());

echo $sprintf->sprintf(
    "php bin/some-cli %(sub-command) %(long-options) %(short-options)",
    [
        'sub-command'  => 'commit',
        'long-options' => [
            'message' => 'Showing off a CLI command',
            'author'  => 'Matt',
        ],
        'short-options' => [
            'a' => null,
        ],
    ]
) . "\n";
?>
```
