<?php
declare(strict_types=1);

namespace Nora\DI;

class Bind
{
    private $container;
    private $interface;

    public function __construct(Container $container, string $interface)
    {
        $this->container = $container;
        $this->interface = $interface;
        $this->validator = new BindValidator;
    }

    public function to(string $class) : self
    {
        $refClass = $this->validator->to($this->interface, $class);

        $this->bound = new Dependency(
            new NewInstance(
                $refClass,
                new SetterMethods([])
            )
        );

        $this->container->add($this);
        return $this;
    }

    public function toConstructor(
        string $class,
        $name,
        InjectionPoints $injection_points = null
    ) : self {
        if (is_array($name)) {
            $name = $this->getStringName($name);
        }

        $this->bound = (new DependencyFactory)->newToConstructor(
            (new NewReflectionClass)($class), $name, $injection_points
        );

        $this->container->add($this);
        return $this;
    }

    public function toInstance($instance) : self
    {
        $this->bound = new Instance($instance);
        $this->container->add($this);
        return $this;
    }

    public function toProvider(string $provider, string $context = '') : self
    {
        $refClass = $this->validator->toProvider($provider);
        $this->bound = (new DependencyFactory)->newProvider(
            $refClass,
            $context
        );
        $this->container->add($this);
        return $this;
    }

    public function getBound()
    {
        return $this->bound;
    }

    public function __toString()
    {
        return $this->interface;
    }

    private function getStringName(array $name) : string
    {
        $names = array_reduce(
            array_keys($name),
            function (array $carry, string $key) use ($name) : array {
                $carry[] = $key . '=' . $name[$key];
                return $carry;
            }, []
        );
        return implode(',', $names);
    }

    public function in(String $scope) : self
    {
          if ($this->bound instanceof Dependency || $this->bound instanceof DependencyProvider) {
            $this->bound->setScope($scope);
          }
          return $this;
    }
}
