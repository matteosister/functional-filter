<?php

namespace Cypress\Value;

use Assert\Assertion;

class Date extends Base
{
    /**
     * @var bool
     */
    private $ascendingOrder;

    /**
     * @var bool
     */
    private $equalCheck;

    /**
     * @param $value
     * @param bool $ascendingOrder
     * @param bool $equalCheck
     */
    public function __construct($value, $ascendingOrder = true, $equalCheck = true)
    {
        $this->value = $value;
        $this->ascendingOrder = $ascendingOrder;
        $this->equalCheck = $equalCheck;
    }

    /**
     * @param $value
     * @return Date
     */
    public static function fromString($value)
    {
        Assertion::notBlank($value);
        if (false === $timestamp = strtotime($value)) {
            throw new \InvalidArgumentException(
                sprintf('The value %s is not a valid strtotime format', $value)
            );
        }
        $value = \DateTime::createFromFormat('U', $timestamp);
        return new self($value);
    }

    /**
     * @return bool
     */
    public function isAscendingOrder()
    {
        return $this->ascendingOrder;
    }

    /**
     * @return bool
     */
    public function isEqualCheck()
    {
        return $this->equalCheck;
    }
}
