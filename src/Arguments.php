<?php
declare(strict_types=1);

namespace Nora\DI;

use Nora\DI\Exception\Unbound;

class Arguments
{
    /**
     * @var Argument[]
     */
    private $arguments = [];

    public function __construct(\ReflectionMethod $method)
    {
        $parameters = $method->getParameters();
        foreach ($parameters as $parameter) {
            $this->arguments[] = new Argument($parameter);
        }
    }

    public function inject(Container $container) : array
    {
        $parameters = $this->arguments;
        foreach ($parameters as &$parameter) {
            $parameter = $this->getParameter($container, $parameter);
        }
        return $parameters;
    }

    private function getParameter(Container $container, Argument $argument)
    {
        try {
            return $container->getDependency((string) $argument);
        } catch (Unbound $e) {
            list ($has_default_value, $default_value) = $this->getDefaultValue($argument);
            if ($has_default_value) {
                return $default_value;
            }

            throw new Unbound($argument->getMeta(), 0, $e);
        }
    }

    private function getDefaultValue(Argument $argument) : array
    {
        if ($argument->isDefaultAvailable()) {
            return [true, $argument->getDefaultValue()];
        }

        return [false, null];
    }

}
