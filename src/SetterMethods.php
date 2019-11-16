<?php

declare(strict_types=1);

namespace Nora\DI;

class SetterMethods
{
    private $methods;

    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }

    public function __invoke($instance, Container $container)
    {
        foreach ($this->methods as $method) {
            ($method)($instance, $container);
        }
    }

    public function add(SetterMethod $method = null)
    {
        if ($method) {
            $this->methods[] = $method;
        }
    }
}
