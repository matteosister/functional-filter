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
        $lessCheck = true;
        $equalCheck = true;
        if (false === $timestamp = strtotime(ltrim($value, '<>='))) {
            throw new \InvalidArgumentException(
                sprintf('The value %s is not a valid strtotime format', $value)
            );
        }
        if (preg_match('/^<(?!=)/', $value)) {
            $lessCheck = true;
            $equalCheck = false;
        }
        if (preg_match('/^<=/', $value)) {
            $lessCheck = true;
            $equalCheck = true;
        }
        if (preg_match('/^>=/', $value)) {
            $lessCheck = false;
            $equalCheck = true;
        }
        if (preg_match('/^>(?!=)/', $value)) {
            $lessCheck = false;
            $equalCheck = false;
        }
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
     * @return Callable
     */
    public function getComparator()
    {
        return function($v) {
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
