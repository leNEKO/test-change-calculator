<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk2Calculator implements CalculatorInterface
{
    use CalculatorTrait;

    private $conf = ['coin2', 'bill5', 'bill10'];
}
