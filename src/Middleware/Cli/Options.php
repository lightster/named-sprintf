<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\ArrayBuilder;
use Lstr\Sprintf\Middleware\InvokableParams;

class Options extends AbstractInvokable
{
    /**
     * @var string
     */
    private $option_type;

    /**
     * @var callable
     */
    private $option_builder;

    /**
     * @param string $option_type
     * @param callable $option_builder
     * @param AbstractInvokable|null $invokable
     */
    public function __construct($option_type, callable $option_builder, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->option_type = $option_type;
        $this->option_builder = $option_builder;
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        if ($this->option_type !== $params->getOption('type', $params->getName())) {
            return;
        }

        $this->prepareOptions($params);

        $params->setValue(implode(' ', $this->getBuiltOptions($params)));
    }

    /**
     * @param InvokableParams $params
     */
    private function prepareOptions(InvokableParams $params)
    {
        $option_preparer = new EscapeShellArgs(new ArrayBuilder());
        $option_preparer->invokeProcess($params);
    }

    /**
     * @param InvokableParams $params
     * @return array
     */
    private function getBuiltOptions(InvokableParams $params)
    {
        $values_callback = $params->getValuesCallback();
        $cli_options = $values_callback($params->getName());

        $built_options = [];
        foreach ($cli_options as $option_name => $option_value) {
            $built_options[] = call_user_func($this->option_builder, $option_name, $option_value);
        }

        return $built_options;
    }
}
