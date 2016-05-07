<?php

namespace Lstr\Sprintf\Middleware;

class ArrayBuilder extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();
        $values_callback = $params->getValuesCallback();

        $values = $values_callback($name);
        if (is_array($values)) {
            return;
        }

        $params->setValue([$name => $values]);
    }
}
