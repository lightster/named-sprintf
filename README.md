named-sprintf
=============

[![Build Status](https://travis-ci.org/lightster/named-sprintf.svg?branch=master)](https://travis-ci.org/lightster/named-sprintf)
[![Test Coverage](https://codeclimate.com/github/lightster/named-sprintf/badges/coverage.svg)](https://codeclimate.com/github/lightster/named-sprintf/coverage)
[![Code Climate](https://codeclimate.com/github/lightster/named-sprintf/badges/gpa.svg)](https://codeclimate.com/github/lightster/named-sprintf)

Enhance PHP sprintf with Python-style named parameters

## Requirements

 - PHP >= 5.5
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

A middleware invokable can be passed to the constructor to process all
parameter values before they are processed by `sprintf`.  This example
takes any parameter passed in as an array and converts it to a
space-delimited string of words before passing the value to `sprintf`:

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
