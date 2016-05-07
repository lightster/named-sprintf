<?php

namespace Lstr\Sprintf\Middleware\Cli;

use PHPUnit_Framework_TestCase;

class LongOptsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::process
     * @dataProvider provideNotLongOpts
     * @param string $name
     * @param array $options
     */
    public function testValueRemainsUnchangedIfTypeIsNotLongOpts($name, array $options)
    {
        $long_opts = new LongOpts();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            $values_callback($name),
            $long_opts($name, $values_callback, $options)
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::<private>
     */
    public function testArrayOfOptionsAreConvertedToString()
    {
        $long_opts = new LongOpts();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "--username='light' --host='localhost' --force --name='\"awe'\''some\"'",
            $long_opts("\"awe'some\"", $values_callback, ['type' => 'long-opts'])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::<private>
     */
    public function testTypeCanBeImpliedFromName()
    {
        $long_opts = new LongOpts();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "--username='light' --host='localhost' --force --name='long-opts'",
            $long_opts("long-opts", $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOpts::<private>
     */
    public function testStringIsUsedAsValue()
    {
        $long_opts = new LongOpts();

        $this->assertSame(
            "--gogo='ogog'",
            $long_opts("gogo", function ($name) {
                return strrev($name);
            }, ['type' => 'long-opts'])
        );
    }

    /**
     * @return array
     */
    public function provideNotLongOpts()
    {
        return [
            ['opts', []],
            ['opts', ['type' => 'custom-type']],
            ['long-opts', ['type' => 'custom-type']],
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
