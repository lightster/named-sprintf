<?php

namespace Lstr\Sprintf\Middleware;

class TypeCast extends AbstractInvokable
{
    /**
     * @var string
     */
    private $default_type;

    /**
     * @param string $default_type
     * @param AbstractInvokable|null $invokable
     */
    public function __construct($default_type = null, AbstractInvokable $invokable = null)
    {
        parent::__construct($invokable);

        $this->default_type = $default_type;
    }

    /**
     * @param InvokableParams $params
     */
    protected function process(InvokableParams $params)
    {
        $name = $params->getName();
        $name_parts = explode('::', $name, 2);
        $params->setName($name_parts[0]);

        $type = $this->getType($name_parts);
        if (null !== $type) {
            $params->setOption('type', $type);
        }
    }

    /**
     * @param array $name_parts
     * @return string|null
     */
    private function getType(array $name_parts)
    {
        if (isset($name_parts[1])) {
            return $name_parts[1];
        }

        if (null !== $this->default_type) {
            return $this->default_type;
        }
    }
}
