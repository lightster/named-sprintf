<?php

namespace Lstr\Sprintf;

class Sprintf
{
    /**
     * @var Processor
     */
    private static $processor;

    /**
     * @param string $format
     * @param array $parameters
     * @param callable $middleware
     * @return string
     */
    public static function sprintf($format, array $parameters, callable $middleware = null)
    {
        return self::getProcessor()->sprintf($format, $parameters, $middleware);
    }

    /**
     * @return Processor
     */
    private static function getProcessor()
    {
        if (self::$processor) {
            return self::$processor;
        }

        self::$processor = new Processor();

        return self::$processor;
    }
}
