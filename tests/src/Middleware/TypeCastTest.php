<?php

namespace Lstr\Sprintf\Middleware;

use Exception;
use PHPUnit_Framework_TestCase;

class TypeCastTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\TypeCast::process
     */
    public function testTypeCastMiddlewareIsAppliedToName()
    {
        $type_cast = $this->getTypeCastMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            [100, 0, 0],
            call_user_func($type_cast, 'red::color-graph', $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\TypeCast::process
     */
    public function testTypeCastCanBeParent()
    {
        $type_cast = $this->getTypeCastMiddleware();
        $color_graph_middleware = $this->getColorGraphMiddleware($type_cast);
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "R:==========\nG:======\nB:\n",
            call_user_func($color_graph_middleware, 'orange::color-graph', $values_callback, [])
        );
        $this->assertSame(
            [100, 60, 0],
            call_user_func($color_graph_middleware, 'orange::blue', $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\TypeCast::process
     */
    public function testTypeCastSetsTypeOption()
    {
        $type_cast = $this->getTypeCastMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParams(
            ['name' => 'orange', 'value' => [100, 60, 0], 'options' => ['type' => 'color-graph']],
            $type_cast,
            ['name' => 'orange::color-graph', 'values_callback' => $values_callback, 'options' => []]
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\TypeCast::__construct
     * @covers Lstr\Sprintf\Middleware\TypeCast::process
     */
    public function testTypeIsDefaultedToBlankByDefault()
    {
        $type_cast = $this->getTypeCastMiddleware();
        $values_callback = $this->getValuesCallback();

        $this->assertParams(
            ['name' => 'orange', 'value' => [100, 60, 0], 'options' => ['type' => '']],
            $type_cast,
            ['name' => 'orange', 'values_callback' => $values_callback, 'options' => []]
        );
    }

    public function testDefaultTypeCanBeOverridden()
    {
        $type_cast = $this->getTypeCastMiddleware('color-graph');
        $values_callback = $this->getValuesCallback();

        $this->assertParams(
            ['name' => 'orange', 'value' => [100, 60, 0], 'options' => ['type' => 'color-graph']],
            $type_cast,
            ['name' => 'orange', 'values_callback' => $values_callback, 'options' => []]
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        $values = [
            'red'    => [100, 0, 0],
            'orange' => [100, 60, 0],
            'yellow::color-graph' => '100,100,0',
        ];

        return function ($name) use ($values) {
            if (!isset($values[$name])) {
                throw new Exception("Param named '{$name}' could not be found.");
            }

            return $values[$name];
        };
    }

    /**
     * @param string $default_type
     * @param AbstractInvokable $parent_middleware
     * @return AbstractInvokable
     */
    private function getTypeCastMiddleware($default_type = null, AbstractInvokable $parent_middleware = null)
    {
        return new TypeCast($default_type, $parent_middleware);
    }

    /**
     * @param AbstractInvokable|null $parent_middleware
     * @return AbstractInvokable
     */
    private function getColorGraphMiddleware(AbstractInvokable $parent_middleware = null)
    {
        return new MiddlewareAdapter(function ($name, callable $values_callback, $options) {
            if ('color-graph' !== $options['type']) {
                return $values_callback($name);
            }

            list($r_pct, $g_pct, $b_pct) = $values_callback($name);

            return "R:" . str_repeat("=", $r_pct / 10) . "\n"
            . "G:" . str_repeat("=", $g_pct / 10) . "\n"
            . "B:" . str_repeat("=", $b_pct / 10) . "\n";
        }, $parent_middleware);
    }

    /**
     * @param array $expected
     * @param AbstractInvokable $middleware
     * @param array $params
     */
    private function assertParams(array $expected, AbstractInvokable $middleware, array $params)
    {
        $assert_middleware = new MiddlewareAdapter(
            function ($name, callable $values_callback, $options) use ($expected) {
                $this->assertSame(
                    $expected,
                    [
                        'name'    => $name,
                        'value'   => $values_callback($name),
                        'options' => $options,
                    ]
                );
            },
            $middleware
        );

        call_user_func($assert_middleware, $params['name'], $params['values_callback'], $params['options']);
    }
}
