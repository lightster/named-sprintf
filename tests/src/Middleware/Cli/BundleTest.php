<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Exception;
use PHPUnit_Framework_TestCase;

class BundleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::process
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::<private>
     */
    public function testBundleHandlesShortOptions()
    {
        $bundle = new Bundle();

        $this->assertSame(
            "-U 'light'",
            $bundle('U::short-options', $this->getValuesCallback(), [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::process
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::<private>
     */
    public function testBundleHandlesLongOptions()
    {
        $bundle = new Bundle();

        $this->assertSame(
            "--username='light'",
            $bundle('username::long-options', $this->getValuesCallback(), [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::process
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::<private>
     */
    public function testBundleHandlesArguments()
    {
        $bundle = new Bundle();

        $this->assertSame(
            "'light'",
            $bundle('username::args', $this->getValuesCallback(), [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::process
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::<private>
     */
    public function testBundleDefaultsTypeToArguments()
    {
        $bundle = new Bundle();

        $this->assertSame(
            "'light'",
            $bundle('username', $this->getValuesCallback(), [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::process
     * @covers Lstr\Sprintf\Middleware\Cli\Bundle::<private>
     */
    public function testBundleCanBeReused()
    {
        $bundle = new Bundle();

        $this->assertSame(
            "--username='light'",
            $bundle('username::long-options', $this->getValuesCallback(), [])
        );
        $this->assertSame(
            "'light'",
            $bundle('username', $this->getValuesCallback(), [])
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        return function ($name) {
            $values = [
                'U'        => 'light',
                'username' => 'light',
            ];
            if (!isset($values[$name])) {
                throw new Exception("Param named '{$name}' could not be found.");
            }

            return $values[$name];
        };
    }
}
