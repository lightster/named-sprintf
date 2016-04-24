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

$welcome_message = Sprintf::sprintf(
    'Hello %(first_name)s %(last_name)s',
    ['first_name' => 'Matt', 'last_name' => 'Light']
);

$pi_message = Sprintf::sprintf(
    'PI is approximately %(pi).5f, or %(pi).8f if you need more accuracy',
    ['pi' => pi()]
);

echo Sprintf::sprintf(
    "%(welcome-message)s\n%(pi-message)s\n",
    ['welcome-message' => $welcome_message, 'pi-message' => $pi_message]
);

```
