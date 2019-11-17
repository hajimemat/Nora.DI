<?php

declare(strict_types=1);

namespace Nora\DI;

interface DependencyInterface
{
    public function inject(Container $container);
    public function register(array &$container, Bind $bind);
    public function setScope($scope);
}
