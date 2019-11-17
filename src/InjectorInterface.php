<?php
declare(strict_types=1);
namespace Nora\DI;

/**
 * 依存性注入
 */
interface InjectorInterface
{
    public function getInstance(string $interface);
}
