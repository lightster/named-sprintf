<?php

namespace Lstr\Sprintf;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Lstr\Sprintf\Sprintf
 */
class SprintfTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::sprintf
     * @covers ::getProcessor
     */
    public function testSprintfDelegatesToProcessor()
    {
        $format = 'my-format: %(my-value)s';
        $params = ['my-value' => uniqid()];
        $middleware = $this->getPassthruMiddleware();

        $processor = $this->getProcessorMock();
        $processor
            ->expects($this->once())
            ->method('parse')
            ->with($format)
            ->willReturn(new ParsedExpression('', []));

        $sprintf = new Sprintf(($middleware = null), $processor);
        $sprintf->sprintf($format, $params);
    }

    /**
     * @covers ::__construct
     * @covers ::sprintf
     * @covers ::getProcessor
     */
    public function testSprintfWillGenerateADefaultProcessorIfNotProvided()
    {
        $sprintf = new Sprintf();
        $this->assertSprintfFormattedUniqueId($sprintf, uniqid());
    }

    /**
     * @covers ::__construct
     * @covers ::sprintf
     * @covers ::getProcessor
     */
    public function testSprintfCanBeReused()
    {
        $sprintf = new Sprintf();
        $this->assertSprintfFormattedUniqueId($sprintf, uniqid());
        $this->assertSprintfFormattedUniqueId($sprintf, uniqid());
    }

    /**
     * @covers ::__construct
     * @covers ::sprintf
     */
    public function testMiddlewarePreprocessesValues()
    {
        $middleware = function ($name, callable $values) {
            $value = $values($name);

            if ('script_path' === $name) {
                return $value;
            }

            return escapeshellarg($value);
        };

        $this->assertEquals(
            "php bin/my-script 'config/config.php'",
            $this->getSprintf($middleware)->sprintf(
                'php %(script_path)s %(config_path)s',
                [
                    'script_path' => 'bin/my-script',
                    'config_path' => 'config/config.php',
                ]
            ),
            "Test that middleware can be provided to pre-process values"
        );
    }

    /**
     * @covers ::__construct
     * @covers ::sprintf
     * @covers ::getProcessor
     */
    public function testMiddlewarePassedToSprintfIsUsedInFormatting()
    {
        $params = [
            'abc' => uniqid(),
        ];

        $sprintf = new Sprintf(function ($name, callable $values_callback) use ($params) {
            $this->assertSame($params[$name], $values_callback($name));
            return "{$params[$name]}{$params[$name]}";
        });
        $this->assertSame(
            "a unique id: {$params['abc']}{$params['abc']}",
            $sprintf->sprintf("a unique id: %(abc)s", $params)
        );
    }

    /**
     * @param callable $middleware
     * @return Sprintf
     */
    private function getSprintf(callable $middleware = null)
    {
        return new Sprintf($middleware);
    }

    /**
     * @return callable
     */
    private function getPassthruMiddleware()
    {
        return function ($name, callable $values_callback) {
            return $values_callback($name);
        };
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getProcessorMock()
    {
        return $this->getMockBuilder('Lstr\Sprintf\Processor')
            ->setMethods(['parse'])
            ->getMock();
    }

    /**
     * @param Sprint $sprintf
     * @param string $unique_id
     */
    private function assertSprintfFormattedUniqueId(Sprintf $sprintf, $unique_id)
    {
        $format = 'my-format: %(my-value)s';
        $params = ['my-value' => $unique_id];

        $this->assertSame(
            "my-format: {$unique_id}",
            $sprintf->sprintf($format, $params)
        );
    }
}
