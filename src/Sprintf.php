<?php

namespace Lstr\Sprintf;

class Sprintf
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param Processor $processor
     */
    public function __construct(Processor $processor = null)
    {
        $this->processor = $processor;
    }

    /**
     * @param string $format
     * @param array $parameters
     * @param callable $middleware
     * @return string
     */
    public function sprintf($format, array $parameters, callable $middleware = null)
    {
        return $this->getProcessor()->sprintf($format, $parameters, $middleware);
    }

    /**
     * @return Processor
     */
    private function getProcessor()
    {
        if ($this->processor) {
            return $this->processor;
        }

        $this->processor = new Processor();

        return $this->processor;
    }
}
