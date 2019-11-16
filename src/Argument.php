<?php
declare(strict_types=1);

namespace Nora\DI;

class Argument
{
    private $index;
    private $is_default_available;
    private $default;
    private $meta;
    private $reflection;

    public function __construct(\ReflectionParameter $parameter)
    {
        $type = $this->getType($parameter);
        $is_optional = $parameter->isOptional();
        $this->is_default_available = $parameter->isDefaultValueAvailable() || $is_optional;
        if ($is_optional) {
            $this->default = null;
        }
        $this->setDefaultValue($parameter);

        $this->index = $type;

        $this->reflection = $parameter;
        $this->meta = sprintf(
            "dependency '%s' used in %s:%d ($%s)",
            $type,
            $this->reflection->getDeclaringFunction()->getFileName(),
            $this->reflection->getDeclaringFunction()->getStartLine(),
            $this->reflection->getName()
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }


    /**
     * @return bool
     */
    public function isDefaultAvailable()
    {
        return $this->is_default_available;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->default;
    }

    private function setDefaultValue(\ReflectionParameter $parameter)
    {
        if (!$this->is_default_available) {
            return;
        }
        try {
            $this->default = $parameter->getDefaultValue();
        } catch (\ReflectionException $e) {
            $this->default = null;
        }
    }

    private function getType(\ReflectionParameter $parameter) : string
    {
        $type = $parameter->getType();
        if ($type instanceof \ReflectionType && in_array((string) $type, ['bool', 'int', 'string', 'array', 'resource', 'callable'], true)) {
            return '';
        }
        return (string) $type;
    }
}
