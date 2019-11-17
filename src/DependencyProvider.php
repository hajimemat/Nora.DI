<?php
declare(strict_types=1);

namespace Nora\DI;

class DependencyProvider implements DependencyInterface
{
    public $context;
    private $dependency;
    private $isSingleton = false;
    private $instance;

    public function __construct(Dependency $dependency, string $context)
    {
        $this->dependency = $dependency;
        $this->context = $context;
    }

    public function __toString()
    {
        return sprintf(
            '(provider) %s',
            (string) $this->dependency
        );
    }

    public function register(array &$container, Bind $bind)
    {
        $container[(string) $bind] = $bind->getBound();
    }

    public function inject(Container $container)
    {
        if ($this->isSingleton && $this->instance) {
            return $this->instance;
        }

        $provider = $this->dependency->inject($container);
        if ($provider instanceof SetContextInterface) {
            $this->setContext($provider);
        }
        $this->instance = $provider->get();

        return $this->instance;
    }

    public function setScope($scope)
    {
        if ($scope === Scope::SINGLETON) {
            $this->isSingleton = true;
        }
    }

    public function setContext(SetContextInterface $provider)
    {
        $provider->setContext($this->context);
    }
}
