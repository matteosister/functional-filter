<?php

namespace Cypress;

use Assert\Assertion;
use Cypress\Value\Factory;
use Cypress\Value\Value;
use PhpCollection\Sequence;
use Functional as F;

class FFilter
{
    private $elements;

    /**
     * @param array|Sequence|\Traversable $elements
     */
    public function __construct($elements)
    {
        if (is_array($elements)) {
            $elements = new Sequence($elements);
        }
        Assertion::implementsInterface($elements, '\Traversable');
        $this->elements = $elements;
    }

    /**
     * @return array|Sequence|\Traversable
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * @param array $filters
     * @return array|Sequence|\Traversable
     */
    public function filter($filters = array())
    {
        if (is_array($filters)) {
            $filters = new Sequence($filters);
        }
        if (is_string($filters)) {
            $filters = new Sequence([$filters]);
        }
        Assertion::implementsInterface($filters, '\Traversable');
        $elements = $this->elements;
        return $filters
            ->map(function ($v) {
                if ($v instanceof Value) {
                    return $v;
                }
                return Factory::create($v);
            })
            ->foldLeft($elements, function(Sequence $elements, Value $value) {
                return $elements->filter($value->getComparator());
            });
    }

    /**
     * @param $method
     * @param $value
     *
     * @return \Closure
     */
    protected function filtererByProperty($method, $value)
    {
        return function ($subject) use ($method, $value) {
            $compareTo = call_user_func(array($subject, $method));
            if (is_array($value)) {
                return $this->fcfCompareArray($compareTo, $value);
            }
            if (is_scalar($value)) {
                return $this->fcfCompareScalar($compareTo, $this->fcfExtractValue($value));
            }
            throw new \RuntimeException('richiesta stringa o scalar');
        };
    }

    /**
     * @param $value
     * @param array $compareTo
     * @return bool
     */
    private function fcfCompareArray($value, array $compareTo)
    {
        $seq = new Sequence($compareTo);
        return $seq
            ->map(function ($v) {
                return $this->fcfExtractValue($v);
            })
            ->exists(function ($v) use ($value) {
                return $this->fcfCompareScalar($value, $v);
            });
    }

    /**
     * @param $value
     * @param $compareTo
     * @return bool
     */
    private function fcfCompareScalar($value, $compareTo)
    {
        if ($compareTo[0]) {
            return $compareTo[1] === $value;
        } else {
            return $compareTo[1] !== $value;
        }
    }

    /**
     * @param $value
     * @return array
     */
    private function fcfExtractValue($value)
    {
        if (is_string($value) && $value[0] == '-') {
            return array(false, substr($value, 1));
        }
        return array(true, $value);
    }
}
