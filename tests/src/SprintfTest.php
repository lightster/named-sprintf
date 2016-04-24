<?php

namespace Lstr\Sprintf;

use PHPUnit_Framework_TestCase;

class SprintfTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideNamedParameterTestData
     * @param string $description
     * @param string $expected
     * @param string $format
     * @param array $params
     */
    public function testNamedParametersAreFormatted($description, $expected, $format, array $params)
    {
        $this->assertEquals(
            $expected,
            Sprintf::sprintf(
                $format,
                $params
            ),
            $description
        );
    }

    /**
     * @expectedException \Lstr\Sprintf\Exception
     */
    public function testUnprovidedNamedParametersThrowAnException()
    {
        Sprintf::sprintf(
            'Hello %(missing_param)',
            ['full_name' => 'There']
        );
    }

    /**
     * @return array
     */
    public function provideNamedParameterTestData()
    {
        return [
            [
                "Test that a basic string can be formatted",
                'Hello Matt Light',
                'Hello %(first_name)s %(last_name)s',
                ['first_name' => 'Matt', 'last_name' => 'Light'],
            ],
            [
                "Test that a floating point number can be formatted",
                'PI is approximately 3.14159',
                'PI is approximately %(pi).5f',
                ['pi' => pi()],
            ],
            [
                "Test that a named parameter can be re-used",
                'PI is approximately 3.14159, or 3.14159265 if you need more accuracy',
                'PI is approximately %(pi).5f, or %(pi).8f if you need more accuracy',
                ['pi' => pi()],
            ],
            [
                "Test that a named parameter can contain a dash",
                'The number 10 is a big number',
                'The number %(big-number)d is a big number',
                ['big-number' => 10],
            ],
            [
                "Test that a named parameter can be used at the beginning of the string",
                'Ciao!',
                '%(hello)s!',
                ['hello' => 'Ciao'],
            ],
            [
                "Test that the % sign can be escaped",
                'x%(y-z)=0',
                'x%%(y-z)=0',
                [],
            ],
            [
                "Test that a % sign can be escaped immediately before a named parameter",
                'x%12=z',
                'x%%%(y)s=z',
                ['y' => 12],
            ],
            [
                "Test that consecutive % signs can be escaped",
                '%%(hi)',
                '%%%%(hi)',
                [],
            ],
            [
                "Test that multiple, separate % signs can be escaped",
                '%(hi there) ... %(hi)',
                '%%(hi there) ... %%(hi)',
                [],
            ],
        ];
    }
}
