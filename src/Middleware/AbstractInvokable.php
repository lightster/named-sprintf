<?php

namespace Lstr\Sprintf\Middleware;

abstract class AbstractInvokable
{
    /**
     * @var AbstractInvokable|null
     */
    private $invokable;

    /**
     * @param AbstractInvokable|null $invokable
     */
    public function __construct(AbstractInvokable $invokable = null)
    {
        $this->invokable = $invokable;
    }

    /**
     * @param string $name
     * @param callable $values
     * @param array $options
     * @return mixed
     */
    public function __invoke($name, callable $values, array $options)
    {
        $params = new InvokableParams($name, $values, $options);

        $this->invokeProcess($params);
        $values_callback = $params->getValuesCallback();

        return $values_callback($params->getName());
    }

    /**
     * @param InvokableParams $params
     */
    abstract protected function process(InvokableParams $params);

    /**
     * @param InvokableParams $params
     */
    private function invokeProcess(InvokableParams $params)
    {
        if ($this->invokable) {
            call_user_func([$this->invokable, 'invokeProcess'], $params);
        }

        $this->process($params);
    }
}
