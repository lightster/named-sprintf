<?php

namespace Lstr\Sprintf;

class Sprintf
{
    /**
     * @var callable
     */
    private $middleware;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @param callable $middleware
     * @param Processor $processor
     */
    public function __construct(callable $middleware = null, Processor $processor = null)
    {
        $this->middleware = $middleware;
        $this->processor = $processor;
    }

    /**
     * @param string $format
     * @param array $parameters
     * @return string
     */
    public function sprintf($format, array $parameters)
    {
        return $this->getProcessor()->sprintf($format, $parameters);
    }

    /**
     * @return Processor
     */
    private function getProcessor()
    {
        if ($this->processor) {
            return $this->processor;
        }

        $this->processor = new Processor($this->middleware);

        return $this->processor;
    }
}
