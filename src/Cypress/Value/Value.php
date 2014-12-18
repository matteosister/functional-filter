<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 18/12/14
 * Time: 22.39
 */

namespace Cypress\Value;

interface Value
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return Callable
     */
    public function getComparator();
}
