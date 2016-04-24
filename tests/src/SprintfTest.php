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
     * @dataProvider provideNamedToUnnamedTranslatedTestCases
     * @param string $unnamed_format
     * @param string $named_format
     * @param string $value
     */
    public function testNamedToUnnamedTranslatedCases($unnamed_format, $named_format, $value)
    {
        $this->assertEquals(
            sprintf($unnamed_format, $value),
            Sprintf::sprintf($named_format, ['value' => $value]),
            "Test that advanced cases can use format '{$named_format}'"
        );
    }

    /**
     * @expectedException \Lstr\Sprintf\Exception
     */
    public function testUnprovidedNamedParametersThrowAnException()
    {
        Sprintf::sprintf(
            'Hello %(missing_param)s',
            ['full_name' => 'There']
        );
    }

    public function testMiddlewarePreprocessesValues()
    {
        $this->assertEquals(
            "php bin/my-script 'config/config.php'",
            Sprintf::sprintf(
                'php %(script_path)s %(config_path)s',
                [
                    'script_path'   => 'bin/my-script',
                    'config_path'   => 'config/config.php',
                ],
                function ($name, $value) {
                    if ('script_path' === $name) {
                        return $value;
                    }

                    return escapeshellarg($value);
                }
            ),
            "Test that middleware can be provided to pre-process values"
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
                "Test that named parameters are only recognized if sprintf directive is valid",
                'Hello %(first_name) Light',
                'Hello %(first_name) %(last_name)s',
                ['first_name' => 'Matt', 'last_name' => 'Light'],
            ],
            [
                "Test that no parameters are required",
                'Hello %(first_name)',
                'Hello %(first_name)',
                [],
            ],
            [
                "Test that a floating point number can be formatted",
                'PI is approximately 3.14159',
                'PI is approximately %(pi).5f',
                ['pi' => pi()],
            ],
            [
                "Test that a named parameter can be re-used consecutively",
                'PI is approximately 3.14159, or 3.14159265 if you need more accuracy',
                'PI is approximately %(pi).5f, or %(pi).8f if you need more accuracy',
                ['pi' => pi()],
            ],
            [
                "Test that a named parameter can be re-used inconsecutively",
                'The name is Bond, James Bond.',
                'The name is %(last_name)s, %(first_name)s %(last_name)s.',
                ['first_name' => 'James', 'last_name' => 'Bond'],
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
                'x%(y-z)s=0',
                'x%%(y-z)s=0',
                ['y-z' => 5],
            ],
            [
                "Test that the unescaped version is formatted",
                'x5=0',
                'x%(y-z)s=0',
                ['y-z' => 5],
            ],
            [
                "Test that a % sign can be escaped immediately before a named parameter",
                'x%12=z',
                'x%%%(y)s=z',
                ['y' => 12],
            ],
            [
                "Test that consecutive % signs can be escaped",
                '%%(hi)s',
                '%%%%(hi)s',
                ['hi' => 'bye'],
            ],
            [
                "Test that formatting can still occur if consecutive % signs can be escaped",
                '%bye',
                '%%%(hi)s',
                ['hi' => 'bye'],
            ],
            [
                "Test that multiple, separate % signs can be escaped",
                '%(hi there)s ... %(hi)s',
                '%%(hi there)s ... %%(hi)s',
                [],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideNamedToUnnamedTranslatedTestCases()
    {
        return [
            ['%b', '%(value)b', '123456789'],
            ['%c', '%(value)c', ord('M')],
            ['%d', '%(value)d', '123456789'],
            ['%e', '%(value)e', '123456789'],
            ['%E', '%(value)E', '123456789'],
            ['%f', '%(value)f', '123456789'],
            ['%F', '%(value)F', '123456789'],
            ['%g', '%(value)g', '123456789'],
            ['%G', '%(value)G', '123456789'],
            ['%o', '%(value)o', '123456789'],
            ['%s', '%(value)s', '123456789'],
            ['%u', '%(value)u', '123456789'],
            ['%x', '%(value)x', '123456789'],
            ['%X', '%(value)X', '123456789'],
            ['%+d', '%(value)+d', 123],
            ['%+d', '%(value)+d', -123],
            ['%+f', '%(value)+f', 123.456],
            ['%+f', '%(value)+f', -123.456],
            ['%+.4f', '%(value)+.4f', 123.456],
            ['%+.4f', '%(value)+.4f', -123.456],
            ['%u', '%(value)u', 123],
            ['%u', '%(value)u', -123],
            ['(%12s)', '(%(value)12s)', 'Pad-me'],
            ['(%-12s)', '(%(value)-12s)', 'Pad-me'],
            ['(%012s)', '(%(value)012s)', 'Pad-me'],
            ['(%-012s)', '(%(value)-012s)', 'Pad-me'],
            ['(%\'.12s)', '(%(value)\'.12s)', 'Pad-me'],
            ['(%\'.-12s)', '(%(value)\'.-12s)', 'Pad-me'],
            ['(%\'.-12.12s)', '(%(value)\'.-12.12s)', 'Pad-me'],
            ['(%\'.-12.12s)', '(%(value)\'.-12.12s)', 'Truncated-me-yet?'],
        ];
    }
}
