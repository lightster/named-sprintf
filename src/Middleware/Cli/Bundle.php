<?php

namespace Lstr\Sprintf\Middleware\Cli;

use Lstr\Sprintf\Middleware\AbstractInvokable;
use Lstr\Sprintf\Middleware\DefaultType;
use Lstr\Sprintf\Middleware\ImpliedTypes;
use Lstr\Sprintf\Middleware\InvokableParams;
use Lstr\Sprintf\Middleware\TypeCast;

class Bundle extends AbstractInvokable
{
    /**
     * @var AbstractInvokable
     */
    private $bundled_middleware;

    /**
     * @param InvokableParams $params
     */
    public function process(InvokableParams $params)
    {
        $this->getBundledMiddleware()->invokeProcess($params);
    }

    /**
     * @return AbstractInvokable
     */
    private function getBundledMiddleware()
    {
        if ($this->bundled_middleware) {
            return $this->bundled_middleware;
        }

        $implied_types = [
            'short-options' => 'short-options',
            'long-options'  => 'long-options',
        ];

        $type_cast = new TypeCast();
        $implied_types = new ImpliedTypes($implied_types, $type_cast);
        $default_type = new DefaultType('args', $implied_types);
        $long_opts = new LongOptions($default_type);
        $short_opts = new ShortOptions($long_opts);
        $arguments = new Arguments($short_opts);

        $this->bundled_middleware = $arguments;

        return $this->bundled_middleware;
    }
}
