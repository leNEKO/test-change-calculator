<?php

declare(strict_types=1);

namespace AppBundle\Registry;

use AppBundle\Calculator\CalculatorInterface;

class CalculatorRegistry implements CalculatorRegistryInterface
{
    /**
     * @param string $model Indicates the model of automaton
     *
     * @return CalculatorInterface|null The calculator, or null if no CalculatorInterface supports that model
     */
    public function getCalculatorFor(string $model): ?CalculatorInterface
    {
        $classpath = '\\AppBundle\\Calculator\\';
        $classname = $classpath . ucfirst($model) . 'Calculator';

        return class_exists($classname) ? new $classname() : null;
    }
}
