<?php

namespace Lstr\Sprintf\Middleware;

use Exception;
use PHPUnit\Framework\TestCase;

class ArrayBuilderTest extends TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\ArrayBuilder::process
     */
    public function testArrayBuilderLeavesArraysUnchanged()
    {
        $array_builder = new ArrayBuilder();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            $values_callback('already-an-array'),
            call_user_func($array_builder, 'already-an-array', $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\ArrayBuilder::process
     */
    public function testArrayBuilderConvertsNonArrayToArrayWithNameAsKey()
    {
        $array_builder = new ArrayBuilder();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            ['not-an-array' => $values_callback('not-an-array')],
            call_user_func($array_builder, 'not-an-array', $values_callback, [])
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        $values = [
            'already-an-array' => [
                'a' => 1,
                'b' => 3,
                'c' => 2,
            ],
            'not-an-array' => 'please-promote-me',
        ];

        return function ($name) use ($values) {
            if (!isset($values[$name])) {
                throw new Exception("Param named '{$name}' could not be found.");
            }

            return $values[$name];
        };
    }
}
