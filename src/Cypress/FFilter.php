<?php

namespace Cypress;

use Assert\Assertion;
use Cypress\Value\Factory;
use Cypress\Value\Value;
use PhpCollection\AbstractCollection;
use PhpCollection\CollectionInterface;
use PhpCollection\Map;
use PhpCollection\Sequence;
use Functional as F;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class FFilter
{
    /**
     * @var Sequence
     */
    private $elements;

    /**
     * @param array|Sequence|\Traversable $elements
     */
    public function __construct($elements)
    {
        $this->elements = $this->normalizeSequence($elements);
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
        $filters = $this->normalizeCollection($filters);
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
        return $this->createValuesFilter($filters)
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
        $elements = $this->elements;
        $valueFilters = $this->createValuesFilter($filters);
        return F\reduce_left($valueFilters, function(Value $valueFilter, $property, $valueFilters, Sequence $elements) {
            return $elements->filter(function ($element) use ($property, $valueFilter) {
                $pa = new PropertyAccessor();
                return $pa->getValue($element, '['.$property.']') === $valueFilter->getValue();
            });
        }, $elements);
    }

    /**
     * given a string, an array, a Sequence or A Map, gives back always a Sequence or a Map
     *
     * @param $collection
     * @return Map|Sequence
     */
    private function normalizeCollection(&$collection)
    {
        if (is_string($collection)) {
            $collection = new Sequence([$collection]);
        }
        if (is_array($collection)) {
            $collection = $this->isAssociative($collection) && count($collection) > 0
                ? new Map($collection)
                : new Sequence($collection);
        }
        Assertion::implementsInterface($collection, '\Traversable');
        return $collection;
    }

    /**
     * given a string, an array, a Sequence, gives back always a Sequence
     *
     * @param $collection
     * @return Map|Sequence
     */
    private function normalizeSequence(&$collection)
    {
        if (is_string($collection)) {
            $collection = new Sequence([$collection]);
        }
        if (is_array($collection)) {
            $collection = new Sequence($collection);
        }
        Assertion::implementsInterface($collection, '\Traversable');
        return $collection;
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
     * @param CollectionInterface $filters
     * @return Map|Sequence
     */
    private function createValuesFilter(CollectionInterface $filters)
    {
        return $this->normalizeCollection(F\map($filters, function ($v) {
            if ($v instanceof Value) {
                return $v;
            }
            return Factory::create($v);
        }));
    }
}
