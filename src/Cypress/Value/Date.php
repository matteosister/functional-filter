<?php

namespace Cypress\Value;

use Assert\Assertion;

class Date extends Base
{
    /**
     * @var bool
     */
    private $lessCheck;

    /**
     * @var bool
     */
    private $equalCheck;

    /**
     * @param $value
     * @param bool $lessCheck
     * @param bool $equalCheck
     */
    public function __construct($value, $lessCheck = true, $equalCheck = true)
    {
        $this->value = $value;
        $this->lessCheck = $lessCheck;
        $this->equalCheck = $equalCheck;
    }

    /**
     * @param $value
     * @return Date
     */
    public static function fromString($value)
    {
        Assertion::notBlank($value);
        Assertion::regex($value, '/^[<>]=?\d{4}-\d{2}-\d{2}/');
        $timestamp = strtotime(ltrim($value, '<>='));
        if (false === $timestamp) {
            throw new \InvalidArgumentException(
                sprintf('The value %s is not a valid strtotime format', $value)
            );
        }
        $lessCheck = (bool) preg_match('/^</', $value);
        $equalCheck = (bool) preg_match('/^[<>]=/', $value);
        $value = \DateTime::createFromFormat('U', $timestamp);
        return new self($value, $lessCheck, $equalCheck);
    }

    /**
     * @return bool
     */
    public function isLessCheck()
    {
        return $this->lessCheck;
    }

    /**
     * @return bool
     */
    public function isEqualCheck()
    {
        return $this->equalCheck;
    }

    /**
     * @return \Closure
     */
    public function getComparator()
    {
        return function ($v) {
            if ($this->isLessCheck()) {
                if ($this->isEqualCheck()) {
                    return $v <= $this->getValue();
                } else {
                    return $v < $this->getValue();
                }
            } else {
                if ($this->isEqualCheck()) {
                    return $v >= $this->getValue();
                } else {
                    return $v > $this->getValue();
                }
            }
        };
    }
}
