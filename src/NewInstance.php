<?php
declare(strict_types=1);

namespace Nora\DI;

class NewInstance
{
    private $class;

    /**
     * @var Arguments
     */
    private $arguments;

    private $setter_methods;

    public function __construct(
        \ReflectionClass $class,
        SetterMethods $setter_methods,
        Name $name = null
    ) {
        $this->class = $class->name;
        $constructor = $class->getConstructor();
        $this->setter_methods = $setter_methods;

        if ($constructor) {
            $this->arguments = new Arguments($constructor);
        }
    }

    public function __invoke(Container $container)
    {
        $instance = $this->arguments instanceof Arguments ?
            (new \ReflectionClass($this->class))->newInstanceArgs(
                $this->arguments->inject($container)
            ): new $this->class;

        return $this->postNewInstance($container, $instance);
    }

    public function postNewInstance(Container $container, $instance)
    {
        ($this->setter_methods)($instance, $container);
        return $instance;
    }
}
