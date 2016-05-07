<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\InvokableParams;

class Arguments extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $opts = new Options('args', function ($option_name, $option_value) {
            return "{$option_value}";
        });
        $opts->invokeProcess($params);
    }
}
