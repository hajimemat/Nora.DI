<?php
declare(strict_types=1);

namespace Nora\DI;

/**
 * カーネルモジュール
 */
abstract class Module implements ModuleInterface
{
    /**
     * @var self ラストモジュール
     */
    private $last_module;

    /**
     * @var Container
     */
    private $container;

    public function __construct(self $module = null)
    {
        $this->last_module = $module;
        $this->activate();
        if ($module instanceof self) {
            $this->container->merge($module->getContainer());
        }
    }

    private function activate()
    {
        $this->container = new Container;
        $this->configure();
    }

    public function getContainer() : Container
    {
        if (!$this->container) {
            $this->activate();
        }
        return $this->container;
    }

    /**
     * 依存性の抽象と具象を関連付ける
     *
     * @param string $interface
     */
    protected function bind(string $interface)
    {
        return new Bind($this->getContainer(), $interface);
    }

    /**
     * インスタンスを取得する
     *
     * @param string $interface
     */
    public function getInstance(string $interface)
    {
        return $this->getContainer()->getInstance($interface);
    }

    /**
     * 他のモジュールから依存性を取得する
     */
    public function install(self $module)
    {
        $this->getContainer()->merge($module->getContainer());
    }

    /**
     * モジュール設定
     */
    abstract public function configure();

    public function override(self $module)
    {
        $module->getContainer()->merge($this->container);;
        $this->container = $module->getContainer();
    }
}
