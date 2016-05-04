<?php

namespace Lstr\Sprintf\Middleware;

class MiddlewareAdapter extends AbstractInvokable
{
    /**
     * @var callable
     */
    private $middleware;

    /**
     * @param callable $middleware
     * @param AbstractInvokable|null $invokable
     */
    public function __construct(callable $middleware, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->middleware = $middleware;
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $params->setValue(call_user_func(
            $this->middleware,
            $params->getName(),
            $params->getValuesCallback(),
            $params->getOptions()
        ));
    }
}
