<?php

namespace Lstr\Sprintf\Middleware;

class ImpliedTypes extends AbstractInvokable
{
    /**
     * @var array
     */
    private $implied_types;

    /**
     * @param array $implied_types
     * @param AbstractInvokable|null $invokable
     */
    public function __construct(array $implied_types, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->implied_types = $implied_types;
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        if (null !== $params->getOption('type')) {
            return;
        }

        if (!array_key_exists($params->getName(), $this->implied_types)) {
            return;
        }

        $params->setOption('type', $this->implied_types[$params->getName()]);
    }
}
