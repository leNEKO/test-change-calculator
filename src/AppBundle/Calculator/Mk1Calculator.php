<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

class Mk1Calculator implements CalculatorInterface
{
    use CalculatorTrait;

    private $conf = ['coin1'];
}
