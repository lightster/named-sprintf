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

## Usage

```php
<?php

use Lstr\Sprintf\Sprintf;

require_once __DIR__ . '/vendor/autoload.php';

$sprintf = new Sprintf(
    function ($name, callable $values) {
        $value = $values($name);

        if (is_array($value)) {
            return implode(' ', $value);
        }

        return $value;
    }
);

$welcome_message = $sprintf->sprintf(
    'Hello %(first_name)s %(last_name)s',
    ['first_name' => 'Matt', 'last_name' => 'Light']
);

$optional_type_message = $sprintf->sprintf(
    'The %(type) is optional and defaults to "%(default_type)"!',
    ['type' => 'type specifier', 'default_type' => '%s']
);

$pi_message = $sprintf->sprintf(
    'PI is approximately %(pi).5f, or %(pi).8f if you need more accuracy',
    ['pi' => pi()]
);

$middleware_message = $sprintf->sprintf(
    'Middleware %(action_words) to pre-process %(what)!',
    [
        'action_words' => ['can', 'be', 'used'],
        'what'         => 'parameters',
    ]
);

echo $sprintf->sprintf(
    "%(welcome)s\n%(optional-type)s\n%(pi)s\n%(middleware)s\n",
    [
        'welcome'       => $welcome_message,
        'optional-type' => $optional_type_message,
        'pi'            => $pi_message,
        'middleware'    => $middleware_message,
    ]
);

```
