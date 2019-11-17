<?php
declare(strict_types=1);
namespace Nora\DI;

use Nora\DI\Exception\Unbound;

/**
 * DIコンテナ
 */
class Container
{
    /**
     * @var Dependency[]
     */
    private $container = [];

    /**
     * コンテナ取得
     *
     * @return Dependency[]
     */
    public function getContainer() : array
    {
        return $this->container;
    }

    /**
     * @param Bind $bind
     */
    public function add(Bind $bind)
    {
        $dependency = $bind->getBound();
        $dependency->register($this->container, $bind);
    }

    /**
     * @param string $interface
     */
    public function getInstance(string $interface)
    {
        return $this->getDependency($interface);
    }

    /**
     * @param string $index
     */
    public function getDependency(string $index)
    {
        if (!isset($this->container[$index])) {
            throw $this->unbound($index);
        }
        $dependency = $this->container[$index];
        return $dependency->inject($this);
    }

    private function unbound(string $index)
    {
        return new Unbound($index);
    }

    public function merge(self $container)
    {
        $this->container += $container->getContainer();
    }
}
