<?php

namespace Lstr\Sprintf;

class Sprintf
{
    /**
     * @param string $format
     * @param array $parameters
     * @return string
     * @throws Exception
     */
    public static function sprintf($format, array $parameters)
    {
        $parsed_parameters = [];
        $replacement_sets = [];
        $matches = [];
        $pattern = '
            /
                (
                    (?:^|[^%])
                    (?:%%)*%
                ) # 1: the percent sign(s)
                \( # literal open paren
                    ([a-zA-Z_][a-zA-Z0-9_\-]*?) # 2: the name of the param 
                \) # literal close paren
            /mx
        ';
        if (preg_match_all($pattern, $format, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $percent_signs = $match[1];
                $named_param = $match[2];

                if (!array_key_exists($named_param, $parameters)) {
                    throw new Exception(
                        "The '{$named_param}' parameter was in the format string but was not provided"
                    );
                }

                $replacement_sets[$match[0]] = "{$percent_signs}";
                $parsed_parameters[] = $parameters[$named_param];
            }
        }

        $searches = array_keys($replacement_sets);
        $replacements = array_values($replacement_sets);
        $parsed_format = str_replace($searches, $replacements, $format);

        return vsprintf($parsed_format, $parsed_parameters);
    }
}
