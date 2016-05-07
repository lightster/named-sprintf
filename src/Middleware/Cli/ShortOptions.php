<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\InvokableParams;

class ShortOptions extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $opts = new Options('short-options', function ($option_name, $option_value) {
            $flag = "-{$option_name}";
            if (null === $option_value) {
                return $flag;
            }

            return "{$flag} {$option_value}";
        });
        $opts->invokeProcess($params);
    }
}
