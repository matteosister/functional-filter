<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 11/12/14
 * Time: 23.07
 */

namespace Cypress\Value;

use Assert\Assertion;

class Bool extends Base
{
    /**
     * @var bool check with equals
     */
    private $positiveCheck;

    /**
     * @var bool strict check
     */
    private $strict;

    /**
     * @param mixed $value         the value to be compared
     * @param bool  $positiveCheck if true ==, if false !=
     * @param bool  $strict        === instead of ==
     */
    public function __construct($value, $positiveCheck = true, $strict = true)
    {
        $this->positiveCheck = $positiveCheck;
        $this->strict = $strict;
        parent::__construct($value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public static function fromString($value)
    {
        Assertion::string($value);
        $positiveCheck = true;
        $strict = true;
        if (empty($value)) {
            return new self($value, $positiveCheck, $strict);
        }
        if ('-' === $value[0]) {
            $positiveCheck = false;
            $value = ltrim($value, '-');
        }
        if ('~' === substr($value, - 1)) {
            $strict = false;
            $value = rtrim($value, '~');
        }
        return new self($value, $positiveCheck, $strict);
    }

    /**
     * @return boolean
     */
    public function isPositiveCheck()
    {
        return $this->positiveCheck;
    }

    /**
     * @return boolean
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     * @return Callable
     */
    public function getComparator()
    {
        return function($v) {
            if ($this->isPositiveCheck()) {
                if ($this->isStrict()) {
                    return $v === $this->getValue();
                } else {
                    return $v == $this->getValue();
                }
            } else {
                if ($this->isStrict()) {
                    return $v !== $this->getValue();
                } else {
                    return $v != $this->getValue();
                }
            }
        };
    }
}
