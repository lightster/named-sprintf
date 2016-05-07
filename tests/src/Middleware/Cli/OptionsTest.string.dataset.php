<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;

return [
    [
        "~name:'\"awe'\''some\"'",
        'cool-options',
        function (AbstractInvokable $invokable = null) {
            return new Options('cool-options', $this->getCoolOptionsBuilder(), $invokable);
        },
        function () {
            return ['name' => "\"awe'some\""];
        }
    ],
    [
        "'\"awe'\''some\"'",
        'args',
        function (AbstractInvokable $invokable = null) {
            return new Arguments($invokable);
        },
        function () {
            return ['name' => "\"awe'some\""];
        }
    ],
    [
        "--name='\"awe'\''some\"'",
        'long-options',
        function (AbstractInvokable $invokable = null) {
            return new LongOptions($invokable);
        },
        function () {
            return ['name' => "\"awe'some\""];
        }
    ],
    [
        "-n '\"awe'\''some\"'",
        'short-options',
        function (AbstractInvokable $invokable = null) {
            return new ShortOptions($invokable);
        },
        function () {
            return ['n' => "\"awe'some\""];
        }
    ],
];
