<?php

namespace Lstr\Sprintf;

class ParsedExpression
{
    /**
     * @var string
     */
    private $parsed_format;

    /**
     * @var array
     */
    private $parameter_map;

    /**
     * @param string $parsed_format
     * @param array $parameter_map
     */
    public function __construct($parsed_format, array $parameter_map)
    {
        $this->parsed_format = $parsed_format;
        $this->parameter_map = $parameter_map;
    }

    /**
     * @param array $parameters
     * @param callable|null $middleware
     * @return string
     * @throws Exception
     */
    public function format(array $parameters, callable $middleware = null)
    {
        if (!$middleware) {
            $middleware = function ($name, callable $values, array $options) {
                return $values($name);
            };
        }

        $values_callback = function ($param_name) use ($parameters) {
            if (!array_key_exists($param_name, $parameters)) {
                throw new Exception(
                    "The '{$param_name}' parameter was in the format string but was not provided"
                );
            }

            return $parameters[$param_name];
        };

        $parsed_parameters = [];
        foreach ($this->parameter_map as $param_mapping) {
            $param_name = $param_mapping['name'];

            $parsed_parameters[] = call_user_func(
                $middleware,
                $param_name,
                $values_callback,
                []
            );
        }

        return vsprintf($this->parsed_format, $parsed_parameters);
    }
}
