<?php

namespace Lstr\Sprintf\Middleware;

use Exception;
use PHPUnit_Framework_TestCase;

class ImpliedTypesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::__construct
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::process
     */
    public function testExplicitTypeIsLeftUnchanged()
    {
        $middleware = $this->getImpliedTypesMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'explicit',
            $middleware,
            ['name' => 'param', 'values_callback' => $values_callback, 'options' => ['type' => 'explicit']]
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::__construct
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::process
     */
    public function testImpliedTypeIsDefaulted()
    {
        $middleware = $this->getImpliedTypesMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'short-options',
            $middleware,
            ['name' => 'short-options', 'values_callback' => $values_callback, 'options' => []]
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
     * @param AbstractInvokable $parent_middleware
     * @return AbstractInvokable
     */
    private function getImpliedTypesMiddleware(AbstractInvokable $parent_middleware = null)
    {
        return new ImpliedTypes(
            [
                'short-options' => 'short-options',
                'long-options'  => 'long-options',
            ],
            $parent_middleware
        );
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