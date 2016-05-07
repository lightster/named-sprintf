<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;

return [
    [
        "~username:'light' ~host:'localhost' ~force ~name:'\"awe'\''some\"'",
        'cool-options',
        function (AbstractInvokable $invokable = null) {
            return new Options('cool-options', $this->getCoolOptionsBuilder(), $invokable);
        },
        function () {
            return ['username' => 'light', 'host' => 'localhost', 'force' => null, 'name' => "\"awe'some\""];
        }
    ],
    [
        "'light' 'localhost'  '\"awe'\''some\"'",
        'args',
        function (AbstractInvokable $invokable = null) {
            return new Arguments($invokable);
        },
        function () {
            return ['username' => 'light', 'host' => 'localhost', 'force' => null, 'name' => "\"awe'some\""];
        }
    ],
    [
        "--username='light' --host='localhost' --force --name='\"awe'\''some\"'",
        'long-options',
        function (AbstractInvokable $invokable = null) {
            return new LongOptions($invokable);
        },
        function () {
            return ['username' => 'light', 'host' => 'localhost', 'force' => null, 'name' => "\"awe'some\""];
        }
    ],
    [
        "-U 'light' -h 'localhost' -f -n '\"awe'\''some\"'",
        'short-options',
        function (AbstractInvokable $invokable = null) {
            return new ShortOptions($invokable);
        },
        function () {
            return ['U' => 'light', 'h' => 'localhost', 'f' => null, 'n' => "\"awe'some\""];
        }
    ],
];
