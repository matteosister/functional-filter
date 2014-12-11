<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 11/12/14
 * Time: 23.04
 */

namespace Cypress\Value;

abstract class Base
{
    /**
     * @var mixed the reference value
     */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
