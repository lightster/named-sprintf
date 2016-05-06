<?php

namespace Lstr\Sprintf\Middleware;

class TypeCast extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();
        $name_parts = explode('::', $name, 2);
        $params->setName($name_parts[0]);
        $params->setOption('type', (isset($name_parts[1]) ? $name_parts[1] : ''));
    }
}
