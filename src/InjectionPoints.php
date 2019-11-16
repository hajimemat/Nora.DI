<?php

declare(strict_types=1);

namespace Nora\DI;

class InjectionPoints
{
    private $points = [];

    public function addMethod(string $method, string $name = Name::ANY) : self
    {
        $this->points[] = [$method, $name, false];
        return $this;
    }

    public function addOptionalMethod(string $method, string $name = Name::ANY) : self
    {
        $this->points[] = [$method, $name, true];
        return $this;
    }

    public function __invoke(string $class) : SetterMethods
    {
        $points = [];

        foreach ($this->points as $point) {
            $points[] = $this->getSetterMethod($class, $point);
        }

        return new SetterMethods($points);
    }

    private function getSetterMethod(string $class, array $point) : SetterMethod
    {
        $setter = new SetterMethod(
            new \ReflectionMethod($class, $point[0]),
            new Name($point[1])
        );
        if ($point[2]) {
            $setter->setOptional();
        }
        return $setter;
    }


}

