<?php

namespace Lstr\Sprintf\Middleware;

use Exception;
use PHPUnit\Framework\TestCase;

class ImpliedTypesTest extends TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::__construct
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::process
     */
    public function testExplicitTypeIsLeftUnchanged()
    {
        $implied_types = $this->getImpliedTypesMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'explicit',
            $implied_types,
            ['name' => 'param', 'values_callback' => $values_callback, 'options' => ['type' => 'explicit']]
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::__construct
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::process
     */
    public function testImpliedTypeIsDefaulted()
    {
        $implied_types = $this->getImpliedTypesMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            'short-options',
            $implied_types,
            ['name' => 'short-options', 'values_callback' => $values_callback, 'options' => []]
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::__construct
     * @covers Lstr\Sprintf\Middleware\ImpliedTypes::process
     */
    public function testUnknownTypeIsLeftUnchanged()
    {
        $middleware = $this->getImpliedTypesMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParamType(
            null,
            $middleware,
            ['name' => 'username', 'values_callback' => $values_callback, 'options' => []]
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
                if (null === $expected) {
                    $this->assertFalse(isset($options['type']));
                    return;
                }

                $this->assertSame($expected, $options['type']);
            },
            $middleware
        );

        call_user_func($assert_middleware, $params['name'], $params['values_callback'], $params['options']);
    }
}
