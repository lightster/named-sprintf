<?php

namespace Lstr\Sprintf\Middleware;

use PHPUnit_Framework_TestCase;

class MiddlewareAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::__construct
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::__invoke
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::invokeProcess
     * @covers Lstr\Sprintf\Middleware\MiddlewareAdapter::__construct
     * @covers Lstr\Sprintf\Middleware\MiddlewareAdapter::process
     */
    public function testMiddlewareAdapterCallbackIsAppliedToValue()
    {
        $meta_adapter = $this->getMetaMiddlewareAdapter();
        $values_callback = $this->getValuesCallback();

        $this->assertEquals(
            "abc:cba:123",
            call_user_func($meta_adapter, 'abc', $values_callback, ['type' => 123])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::__construct
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::__invoke
     * @covers Lstr\Sprintf\Middleware\AbstractInvokable::invokeProcess
     * @covers Lstr\Sprintf\Middleware\MiddlewareAdapter::__construct
     * @covers Lstr\Sprintf\Middleware\MiddlewareAdapter::process
     */
    public function testMiddlewareAdapterCallbackCanBeNested()
    {
        $repeat_adapter = $this->getRepeatMiddlewareAdapter();
        $meta_adapter = $this->getMetaMiddlewareAdapter($repeat_adapter);
        $values_callback = $this->getValuesCallback();

        $this->assertEquals(
            "abc:cbacbacba:123",
            call_user_func($meta_adapter, 'abc', $values_callback, ['type' => 123, 'repeat' => 3])
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        return function ($name) {
            return strrev($name);
        };
    }

    /**
     * @param MiddlewareAdapter $parent_middleware
     * @return MiddlewareAdapter
     */
    private function getRepeatMiddlewareAdapter(MiddlewareAdapter $parent_middleware = null)
    {
        return new MiddlewareAdapter(function ($name, callable $values_callback, $options) {
            return str_repeat($values_callback($name), $options['repeat']);
        }, $parent_middleware);
    }

    /**
     * @param MiddlewareAdapter|null $parent_middleware
     * @return MiddlewareAdapter
     */
    private function getMetaMiddlewareAdapter(MiddlewareAdapter $parent_middleware = null)
    {
        return new MiddlewareAdapter(function ($name, callable $values_callback, $options) {
            return "{$name}:" . $values_callback($name) . ":" . $options['type'];
        }, $parent_middleware);
    }
}
