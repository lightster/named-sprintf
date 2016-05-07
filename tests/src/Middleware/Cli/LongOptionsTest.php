<?php

namespace Lstr\Sprintf\Middleware\Cli;

use PHPUnit_Framework_TestCase;

class LongOptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @dataProvider provideNotLongOptions
     * @param string $name
     * @param array $options
     */
    public function testValueRemainsUnchangedIfTypeIsNotLongOptions($name, array $options)
    {
        $long_opts = new LongOptions();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            $values_callback($name),
            $long_opts($name, $values_callback, $options)
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::<private>
     */
    public function testArrayOfOptionsAreConvertedToString()
    {
        $long_opts = new LongOptions();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "--username='light' --host='localhost' --force --name='\"awe'\''some\"'",
            $long_opts("\"awe'some\"", $values_callback, ['type' => 'long-options'])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::<private>
     */
    public function testTypeCanBeImpliedFromName()
    {
        $long_opts = new LongOptions();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "--username='light' --host='localhost' --force --name='long-options'",
            $long_opts("long-options", $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::<private>
     */
    public function testStringIsUsedAsValue()
    {
        $long_opts = new LongOptions();

        $this->assertSame(
            "--gogo='ogog'",
            $long_opts("gogo", function ($name) {
                return strrev($name);
            }, ['type' => 'long-options'])
        );
    }

    /**
     * @return array
     */
    public function provideNotLongOptions()
    {
        return [
            ['opts', []],
            ['opts', ['type' => 'custom-type']],
            ['long-options', ['type' => 'custom-type']],
        ];
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        return function ($name) {
            return ['username' => 'light', 'host' => 'localhost', 'force' => null, 'name' => $name];
        };
    }
}
