<?php
declare(strict_types=1);
namespace Nora\DI;

class DependencyFactory
{
    public function newToConstructor(
        \ReflectionClass $class,
        string $name,
        InjectionPoints $injection_points = null,
        \ReflectionMethod $post_construct = null
    ) : Dependency {

        $setter = $injection_points ? $injection_points($class->name): new SetterMethods([]);
        $new_instance = new NewInstance($class, $setter, new Name($name));

        return new Dependency($new_instance, $post_construct);
    }

    public function newProvider(
        \ReflectionClass $class,
        string $context
    ) : DependencyProvider {
        $dependency = new Dependency(
            new NewInstance($class, new SetterMethods([]), new Name(Name::ANY))
        );
        return new DependencyProvider($dependency, $context);
    }
}
