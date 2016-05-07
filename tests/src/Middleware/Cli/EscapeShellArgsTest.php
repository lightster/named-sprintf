<?php

namespace Lstr\Sprintf\Middleware\Cli;

use PHPUnit_Framework_TestCase;

class EscapeShellArgsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::process
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::<private>
     */
    public function testStringOptionsAreEscaped()
    {
        $escape_shell_args = new EscapeShellArgs();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            "'\"str'\''ing\"'",
            $escape_shell_args('string', $values_callback, [])
        );
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::process
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::<private>
     */
    public function testNullOptionsAreLeftAlone()
    {
        $escape_shell_args = new EscapeShellArgs();
        $values_callback = $this->getValuesCallback();

        $this->assertNull($escape_shell_args('null', $values_callback, []));
    }

    /**
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::process
     * @covers Lstr\Sprintf\Middleware\Cli\EscapeShellArgs::<private>
     */
    public function testArrayOfOptionsAreEscaped()
    {
        $escape_shell_args = new EscapeShellArgs();
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            [
                'username' => "'light'",
                'host'     => "'localhost'",
                'force'    => null,
                'name'     => "'\"awe'\''some\"'",
            ],
            $escape_shell_args("\"awe'some\"", $values_callback, [])
        );
    }

    /**
     * @return callback
     */
    private function getValuesCallback()
    {
        return function ($name) {
            if ('string' === $name) {
                return "\"str'ing\"";
            }

            if ('null' === $name) {
                return null;
            }

            return ['username' => 'light', 'host' => 'localhost', 'force' => null, 'name' => $name];
        };
    }
}
