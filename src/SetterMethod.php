<?php

declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\Exception\Unbound;

class SetterMethod
{
    private $method;
    private $arguments;
    private $is_optional;

    public function __construct(\ReflectionMethod $method, Name $name)
    {
        $this->method = $method->name;
        $this->arguments = new Arguments($method, $name);
    }

    public function __invoke($instance, Container $container)
    {
        try {
            $parameters = $this->arguments->inject($container);
        } catch (Unbound $e) {
            if ($this->is_optional) {
                return;
            }

            throw $e;
        }
        $callable = [$instance, $this->method];
        call_user_func_array($callable, $parameters);
    }

    public function setOptional()
    {
        $this->is_optional = true;
    }
}
