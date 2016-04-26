<?php

namespace Lstr\Sprintf;

class Processor
{
    /**
     * @param string $format
     * @param array $parameters
     * @param callable $middleware
     * @return string
     * @throws Exception
     */
    public function sprintf($format, array $parameters, callable $middleware = null)
    {
        $parameter_map = [];
        $replacement_sets = [];
        $matches = [];
        $pattern = '
            @
                (
                    (?:^|[^%])
                    (?:%%)*%
                ) # 1: the percent sign(s)
                \( # literal open paren
                    ([a-zA-Z_][a-zA-Z0-9_\-]*?) # 2: the name of the param
                \) # literal close paren

                (
                    (?:
                        # standard sprintf format. reference http://php.net/sprintf
                        [+]?                # an optional +/- sign specifier

                        (?:[ 0]|\'.)?       # an optional space/zero padding specifier or
                                            # alternative padding character prefixed by a single quote

                        [\-]?               # an optional alignment specifier
                                            # ("-" for left-justified; right-justified otherwise)

                        \d*                 # an optional width specifier

                        (?:\.(?:.?\d+)?)?   # an optional precision specifier
                                            # (a dot "." followed by an optional number, with an optional
                                            # padding character in between the dot and the number)

                        [bcdeEfFgGosuxX]    # the type specifier
                    )?

                ) # 3: the standard sprintf format
            @mx
        ';
        if (preg_match_all($pattern, $format, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $percent_signs = $match[1];
                $named_param = $match[2];
                $sprintf_format = $match[3];

                // if it is not a valid sprintf directive, escape the %
                if (!$sprintf_format) {
                    $replacement_sets[$match[0]] = "{$percent_signs}%({$named_param})";
                    continue;
                }

                $replacement_sets[$match[0]] = "{$percent_signs}{$sprintf_format}";
                $parameter_map[] = [
                    'name' => $named_param,
                ];
            }
        }

        $searches = array_keys($replacement_sets);
        $replacements = array_values($replacement_sets);
        $parsed_format = str_replace($searches, $replacements, $format);

        $parsed_expression = new ParsedExpression($parsed_format, $parameter_map);

        return $parsed_expression->format($parameters, $middleware);
    }
}
