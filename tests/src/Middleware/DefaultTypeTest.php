<?php

namespace Lstr\Sprintf\Middleware;

use Exception;
use PHPUnit_Framework_TestCase;

class DefaultTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\DefaultType::__construct
     * @covers Lstr\Sprintf\Middleware\DefaultType::process
     */
    public function testExplicitTypeIsLeftUnchanged()
    {
        $default_type_middleware = $this->getDefaultTypeMiddleware('default');
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'explicit',
            $default_type_middleware,
            ['name' => 'param', 'values_callback' => $values_callback, 'options' => ['type' => 'explicit']]
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\DefaultType::__construct
     * @covers Lstr\Sprintf\Middleware\DefaultType::process
     */
    public function testMissingTypeIsDefaulted()
    {
        $default_type_middleware = $this->getDefaultTypeMiddleware('default');
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'default',
            $default_type_middleware,
            ['name' => 'param', 'values_callback' => $values_callback, 'options' => []]
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        return function ($name) {};
    }

    /**
     * @param string $default_type
     * @param AbstractInvokable $parent_middleware
     * @return AbstractInvokable
     */
    private function getDefaultTypeMiddleware($default_type, AbstractInvokable $parent_middleware = null)
    {
        return new DefaultType($default_type, $parent_middleware);
    }

    /**
     * @param string $expected
     * @param AbstractInvokable $middleware
     * @param array $params
     */
    private function assertParamType($expected, AbstractInvokable $middleware, array $params)
    {
        $assert_middleware = new MiddlewareAdapter(
            function ($name, callable $values_callback, $options) use ($expected) {
                $this->assertSame($expected, $options['type']);
            },
            $middleware
        );

        call_user_func($assert_middleware, $params['name'], $params['values_callback'], $params['options']);
    }
}
