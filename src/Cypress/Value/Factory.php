<?php

namespace Cypress\Value;

class Factory
{
    /**
     * @param $filter
     * @return Bool
     */
    public static function create($filter)
    {
        return new Bool($filter);
    }
}
