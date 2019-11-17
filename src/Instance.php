<?php

declare(strict_types=1);

namespace Nora\DI;

class Instance implements DependencyInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        if (is_scalar($this->value)) {
            return sprintf(
                '(%s) %s',
                gettype($this->value),
                (string) $this->value
            );
        }

        if (is_object($this->value)) {
            return '(object) ' . get_class($this->value);
        }

        return '(' . gettype($this->value) .')';
    }

    public function register(array &$container, Bind $bind)
    {
        $index = (string) $bind;
        $container[$index] = $bind->getBound();
    }

    public function inject(Container $container)
    {
        unset($container);
        return $this->value;
    }

    public function setScope($scope)
    {
    }
}
