<?php

namespace Lstr\Sprintf\Middleware;

use Exception;

class InvokableParams
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $values_callback;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $override_values;

    /**
     * @param string $name
     * @param callable $values_callback
     * @param array $options
     */
    public function __construct($name, callable $values_callback, array $options)
    {
        $this->name = $name;
        $this->values_callback = function ($name) use ($values_callback) {
            if (array_key_exists($name, $this->override_values)) {
                return $this->override_values[$name];
            }

            return $values_callback($name);
        };
        $this->options = $options;
        $this->override_values = [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return callable
     */
    public function getValuesCallback()
    {
        return $this->values_callback;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->override_values[$this->getName()] = $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addValue($name, $value)
    {
        $this->override_values[$name] = $value;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }
}
