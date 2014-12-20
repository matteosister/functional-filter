<?php

namespace Cypress;

use Assert\Assertion;
use Cypress\Value\Factory;
use Cypress\Value\Value;
use PhpCollection\Map;
use PhpCollection\Sequence;

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
     * @param $elements
     * @return FFilter
     */
    public static function init($elements)
    {
        return new self($elements);
    }

    /**
     * @return \Traversable
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * @param array $filters
     * @return array|Sequence|Map|\Traversable
     */
    public function filter($filters = array())
    {
        $this->normalizeFilters($filters);
        if ($filters instanceof Sequence) {
            return $this->handleSequence($filters);
        }
        if ($filters instanceof Map) {
            return $this->handleMap($filters);
        }
    }

    /**
     * @param Sequence $filters
     * @return Sequence
     */
    private function handleSequence(Sequence $filters)
    {
        $elements = $this->elements;
        return $filters
            ->map(function ($v) {
                if ($v instanceof Value) {
                    return $v;
                }
                return Factory::create($v);
            })
            ->foldLeft($elements, function (Sequence $elements, Value $value) {
                return $elements->filter($value->getComparator());
            });
    }

    /**
     * @param Map $filters
     * @return array|Sequence|\Traversable
     */
    private function handleMap(Map $filters)
    {
        return $this->elements;
    }

    /**
     * given a string, an array, a Sequence or A Map, gives back always a Sequence or a Map
     *
     * @param $filters
     */
    private function normalizeFilters(&$filters)
    {
        if ($filters instanceof Sequence || $filters instanceof Map) {
            return;
        }
        if (is_string($filters)) {
            $filters = new Sequence([$filters]);
        }
        if (is_array($filters)) {
            $filters = $this->isAssociative($filters) && count($filters) > 0
                ? new Map($filters)
                : new Sequence($filters);
        }
        Assertion::implementsInterface($filters, '\Traversable');
    }

    /**
     * whether the array is associative or not
     *
     * @param $arr
     * @return bool
     */
    public function isAssociative($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
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
            $compareTo = call_user_func([$subject, $method]);
            if (is_array($value)) {
                return $this->fcfCompareArray($compareTo, $value);
            }
            if (is_scalar($value)) {
                return $this->fcfCompareScalar($compareTo, $this->fcfExtractValue($value));
            }
            throw new \RuntimeException('String or scalar requested');
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
            return [false, substr($value, 1)];
        }
        return [true, $value];
    }
}
