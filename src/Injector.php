<?php
declare(strict_types=1);
namespace Nora\DI;

/**
 * 依存性注入
 */
class Injector implements InjectorInterface
{
    private $module;

    public function __construct(ModuleInterface $module)
    {
        $this->module = $module;
    }

    public function getInstance(string $interface)
    {
        return $this->module->getInstance($interface);
    }
}
