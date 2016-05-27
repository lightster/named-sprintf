<?php

namespace Lstr\Sprintf\Middleware;

class DefaultType extends AbstractInvokable
{
    /**
     * @var string
     */
    private $default_type;

    /**
     * @param string $default_type
     * @param AbstractInvokable|null $invokable
     */
    public function __construct($default_type, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->default_type = $default_type;
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        if (null !== $params->getOption('type')) {
            return;
        }

        $params->setOption('type', $this->default_type);
    }
}
