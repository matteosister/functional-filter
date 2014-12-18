<?php

namespace Cypress\Value;

class Factory
{
    /**
     * @param $stringFilter
     * @return Bool
     */
    public static function create($stringFilter)
    {
        return new Bool($stringFilter);
    }
}
