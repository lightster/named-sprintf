<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Exception;
use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\MiddlewareAdapter;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    /**
     * @covers Lstr\Sprintf\Middleware\Cli\Options::process
     * @covers Lstr\Sprintf\Middleware\Cli\Arguments::process
     * @covers Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers Lstr\Sprintf\Middleware\Cli\ShortOptions::process
     * @dataProvider provideMismatchedType
     * @param string $name
     * @param array $options
     */
    public function testValueRemainsUnchangedIfTypeDoesNotMatch($name, array $options)
    {
        $options_middleware = new Options('mismatched-type', function () {
            throw new Exception("A value should not be requested in this test case!");
        });
        $values_callback = $this->getValuesCallback();

        $this->assertSame(
            $values_callback($name),
            $options_middleware($name, $values_callback, $options)
        );
    }

    /**
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::__construct
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::process
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::<private>
     * @covers       Lstr\Sprintf\Middleware\Cli\Arguments::process
     * @covers       Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers       Lstr\Sprintf\Middleware\Cli\ShortOptions::process
     * @dataProvider provideOptionConfigsForArrayTest
     * @param string $expected
     * @param string $option_type
     * @param callable $middleware_factory
     * @param callable $values_callback
     */
    public function testStringIsConvertedToStringOption(
        $expected,
        $option_type,
        callable $middleware_factory,
        callable $values_callback
    ) {
        $options_middleware = $middleware_factory();

        $this->assertSame(
            $expected,
            $options_middleware("irrelevant", $values_callback, ['type' => $option_type])
        );
    }

    /**
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::__construct
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::process
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::<private>
     * @covers       Lstr\Sprintf\Middleware\Cli\Arguments::process
     * @covers       Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers       Lstr\Sprintf\Middleware\Cli\ShortOptions::process
     * @dataProvider provideOptionConfigsForArrayTest
     * @param string $expected
     * @param string $option_type
     * @param callable $middleware_factory
     * @param callable $values_callback
     */
    public function testArrayOfOptionsAreConvertedToStringOfEscapedOptions(
        $expected,
        $option_type,
        callable $middleware_factory,
        callable $values_callback
    ) {
        $options_middleware = $middleware_factory();

        $this->assertSame(
            $expected,
            $options_middleware("irrelevant", $values_callback, ['type' => $option_type])
        );
    }

    /**
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::__construct
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::process
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::<private>
     * @covers       Lstr\Sprintf\Middleware\Cli\Arguments::process
     * @covers       Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers       Lstr\Sprintf\Middleware\Cli\ShortOptions::process
     * @dataProvider provideOptionConfigsForArrayTest
     * @param string $expected
     * @param string $option_type
     * @param callable $middleware_factory
     * @param callable $values_callback
     */
    public function testTypeCanBeImpliedFromName(
        $expected,
        $option_type,
        callable $middleware_factory,
        callable $values_callback
    ) {
        $options_middleware = $middleware_factory();

        $this->assertSame(
            $expected,
            $options_middleware($option_type, $values_callback, [])
        );
    }

    /**
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::__construct
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::process
     * @covers       Lstr\Sprintf\Middleware\Cli\Options::<private>
     * @covers       Lstr\Sprintf\Middleware\Cli\Arguments::process
     * @covers       Lstr\Sprintf\Middleware\Cli\LongOptions::process
     * @covers       Lstr\Sprintf\Middleware\Cli\ShortOptions::process
     * @dataProvider provideOptionConfigsForStringTest
     * @param string $expected
     * @param string $option_type
     * @param callable $middleware_factory
     * @param callable $values_callback
     */
    public function testParentMiddlewareIsCalled(
        $expected,
        $option_type,
        callable $middleware_factory,
        callable $values_callback
    ) {
        $some_to_none = new MiddlewareAdapter(function ($name, callable $values_callback) {
            return str_replace('some', 'none', $values_callback($name));
        });
        $options_middleware = $middleware_factory($some_to_none);

        $this->assertSame(
            str_replace('some', 'none', $expected),
            $options_middleware("irrelevant", $values_callback, ['type' => $option_type])
        );
    }

    /**
     * @return array
     */
    public function provideMismatchedType()
    {
        return [
            ['opts', []],
            ['opts', ['type' => 'custom-type']],
            ['long-opts', ['type' => 'custom-type']],
        ];
    }

    /**
     * @return array
     */
    public function provideOptionConfigsForStringTest()
    {
        return require __DIR__ . '/OptionsTest.string.dataset.php';
    }

    /**
     * @return array
     */
    public function provideOptionConfigsForArrayTest()
    {
        return require __DIR__ . '/OptionsTest.array.dataset.php';
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

    /**
     * @return callable
     */
    private function getCoolOptionsBuilder()
    {
        return function ($option_name, $option_value) {
            $flag = "~{$option_name}";
            if (null === $option_value) {
                return $flag;
            }

            return "{$flag}:{$option_value}";
        };
    }
}
