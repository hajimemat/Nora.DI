<?php
declare(strict_types=1);
namespace Nora\DI;

class Dependency
{
    private $newInstance;

    private $index;

    private $instance;

    public function __construct(NewInstance $newInstance)
    {
        $this->newInstance = $newInstance;
    }

    public function register(array &$container, Bind $bind)
    {
        $this->index = $index = (string) $bind;
        $container[$index] = $bind->getBound();
    }

    public function inject(Container $container)
    {
        if ($this->instance) {
            return $this->instance;
        }

        $this->instance = ($this->newInstance)($container);

        return $this->instance;
    }
}
