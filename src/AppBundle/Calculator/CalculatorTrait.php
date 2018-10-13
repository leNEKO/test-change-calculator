<?php

declare(strict_types=1);

namespace AppBundle\Calculator;

use AppBundle\Model\Change;

trait CalculatorTrait
{
    /**
     * @return string Indicates the model of automaton
     */
    public function getSupportedModel(): string
    {
        // let's extract model name from the class name
        preg_match("/(?<model>\w+)Calculator/", get_class($this), $m);

        return strtolower($m['model']);
    }

    /**
     * ! this one need dynamical programming
     * ! i am near enough competent to recognize such a problem,
     * ! but not really good at implement it yet,
     * ! that's some translation of a python solution i like (not from me)
     * https://exercism.io/tracks/python/exercises/change/solutions/a6ce69b6881f4204aad4ecdddb89f877
     *
     * @param int $amount The amount of money to turn into change
     *
     * @return Change|null The change, or null if the operation is impossible
     */
    public function getChange(int $amount): ?Change
    {
        // if empty change
        if (0 === $amount) {
            return new Change();
        }

        // the change map as coin values array
        $coins = $this->getChangeValueMap();

        // init empty best combinations list
        $bests = array_fill(
            1,
            $amount + max($coins),
            -1
        );
        $bests[0] = null;

        foreach (range(0, $amount) as $i) {
            if (-1 === $bests[$i]) {
                continue;
            }
            foreach ($coins as $c) {
                $cs = $bests[$i];
                $cs[] = $c;
                if (
                    -1 === $bests[$c + $i]
                    || count($cs) < count((array) $bests[$c + $i])
                ) {
                    sort($cs);
                    $bests[$c + $i] = $cs;
                }
            }
        }
        $solution = $bests[$amount];

        // not any solution found
        if (-1 === $solution) {
            return null;
        }

        // convert this $solution array to a Change object
        //* probably not the idiomatioc way doing this with symfony …
        $result = new Change();
        $change_map = array_flip($coins);
        foreach ((array) $solution as $coin) {
            $key = $change_map[$coin];
            $result->$key++;
        }

        return $result;
    }

    /**
     * Build an associative array from the $conf property
     *
     * @return array Change property => integer value
     */
    public function getChangeValueMap(): array
    {
        $change_map = [];
        foreach ($this->conf as $key) {
            // extract the int part of the property name
            preg_match("/(?<value>\d+)$/", $key, $m);
            $change_map[$key] = (int) $m['value'];
        }

        return $change_map;
    }

    public function __construct()
    {
        // check if values in $conf are part of Model\Change property
        $change_keys = array_keys((array) new Change());
        if ($invalid_keys = array_diff($this->conf, $change_keys)) {
            throw new \Exception(implode(',', $invalid_keys) . ' : invalid Change property');
        }
    }
}
