<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\ArrayBuilder;
use Lstr\Sprintf\Middleware\InvokableParams;

class EscapeShellArgs extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();

        $values_callback = $params->getValuesCallback();
        $args = $values_callback($name);

        if (!is_array($args)) {
            $new_args = $this->escapeShellArg($args);
        } else {
            $new_args = [];
            foreach ($args as $key => $value) {
                $new_args[$key] = $this->escapeShellArg($value);
            }
        }

        $params->setValue($new_args);
    }

    /**
     * @param $arg
     * @return string|null
     */
    private function escapeShellArg($arg)
    {
        if (null === $arg) {
            return $arg;
        }

        return escapeshellarg($arg);
    }
}
