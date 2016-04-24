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
     * @return string
     * @throws Exception
     */
    public static function sprintf($format, array $parameters)
    {
        return self::getProcessor()->sprintf($format, $parameters);
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
