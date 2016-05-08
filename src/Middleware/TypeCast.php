<?php

namespace Lstr\Sprintf\Middleware;

class TypeCast extends AbstractInvokable
{
    /**
     * @var string
     */
    private $default_type;

    /**
     * @param string $default_type
     * @param AbstractInvokable|null $invokable
     */
    public function __construct($default_type = null, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->default_type = (null === $default_type ? '' : $default_type);
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();
        $name_parts = explode('::', $name, 2);
        $params->setName($name_parts[0]);
        $params->setOption('type', (isset($name_parts[1]) ? $name_parts[1] : $this->default_type));
    }
}
