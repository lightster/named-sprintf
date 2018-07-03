<?php

namespace Lstr\Sprintf;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Lstr\Sprintf\ParsedExpression
 */
class ParsedExpressionTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::format
     * @dataProvider provideTestParameters
     * @param array $parameters
     */
    public function testParsedFormatIsUsedToFormatString(array $parameters)
    {
        $parsed_expression = $this->getParsedExpression();

        $this->assertSame(
            '98.123450 abc 12 98.123450',
            $parsed_expression->format($parameters)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::format
     * @dataProvider provideTestParameters
     * @param array $parameters
     */
    public function testMiddlewareIsAppliedToEachParameter(array $parameters)
    {
        $parsed_expression = $this->getParsedExpression();

        $middleware = function ($name, callable $value_callback) {
            $value = $value_callback($name);

            if (!is_numeric($value)) {
                return $value;
            }

            return 2 * $value;
        };

        $this->assertSame(
            '196.246900 abc 24 196.246900',
            $parsed_expression->format($parameters, $middleware)
        );
    }

    /**
     * @covers ::__construct
     * @covers ::format
     * @expectedException Lstr\Sprintf\Exception
     */
    public function testMissingParameterCausesAnException()
    {
        $parsed_expression = new ParsedExpression('', [['name' => 'one']]);

        $parsed_expression->format([]);
    }

    /**
     * @return array
     */
    public function provideTestParameters()
    {
        return [
            [[
                'string'  => 'abc',
                'integer' => 12.3,
                'float'   => 98.12345,
            ]],
        ];
    }

    /**
     * @return ParsedExpression
     */
    private function getParsedExpression()
    {
        $parsed_format = '%f %s %d %f';
        $parameter_map = [
            ['name' => 'float'],
            ['name' => 'string'],
            ['name' => 'integer'],
            ['name' => 'float'],
        ];
        return new ParsedExpression($parsed_format, $parameter_map);
    }
}
