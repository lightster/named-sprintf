<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\InvokableParams;

class LongOpts extends AbstractInvokable
{
    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();

        if ('long-opts' !== $params->getOption('type', $name)) {
            return;
        }

        $cli_options = $this->getValuesAsArray($params, $name);

        $built_options = [];
        foreach ($cli_options as $option_name => $option_value) {
            $built_options[] = $this->buildOption($option_name, $option_value);
        }

        $params->setValue(implode(' ', $built_options));
    }

    /**
     * @param InvokableParams $params
     * @param string $name
     * @return array
     */
    private function getValuesAsArray(InvokableParams $params, $name)
    {
        $values_callback = $params->getValuesCallback();
        $values = $values_callback($name);
        if (is_array($values)) {
            return $values;
        }

        return [$name => $values];
    }

    /**
     * @param string $option_name
     * @param string|null $option_value
     * @return string
     */
    private function buildOption($option_name, $option_value)
    {
        $flag = "--{$option_name}";
        if (null === $option_value) {
            return $flag;
        }

        return "{$flag}=" . escapeshellarg($option_value);
    }
}
