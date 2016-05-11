<?php

namespace Lstr\Sprintf;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass Lstr\Sprintf\Sprintf
 */
class SprintfTest extends PHPUnit_Framework_TestCase
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
            ->method('sprintf')
            ->with($format, $params, $middleware);

        $sprintf = new Sprintf($processor);
        $sprintf->sprintf($format, $params, $middleware);
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
     * @return Sprintf
     */
    private function getSprintf()
    {
        return new Sprintf();
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
            ->setMethods(['sprintf'])
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
        $middleware = $this->getPassthruMiddleware();

        $this->assertSame(
            "my-format: {$unique_id}",
            $sprintf->sprintf($format, $params, $middleware)
        );
    }
}
