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
        if (is_string($stringFilter)) {
            return Bool::fromString($stringFilter);
        }
        return new Bool($stringFilter);
    }
}
