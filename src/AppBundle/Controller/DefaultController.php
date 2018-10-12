<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Registry\CalculatorRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/automaton/{model}/change/{amount}", name= "get_change")
     */
    public function get_change(string $model, int $amount)
    {
        // get calculator
        $calculator = (new CalculatorRegistry())->getCalculatorFor($model);

        // if no calculator founds
        if (null === $calculator) {
            return (new Response())->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        // default status
        $status = Response::HTTP_OK;

        // if no change solution
        if (!$change = $calculator->getChange($amount)) {
            $status = Response::HTTP_NO_CONTENT;
        }

        // yay! success
        return $this->json($change)->setStatusCode($status);
    }
}
