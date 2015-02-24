<?php

namespace Cypress\FunctionalFilter;

use Functional as F;
use React\Partial as P;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @param array $subjects
 * @param $filter
 *
 * @return array
 */
function filter(array $subjects, $filter)
{
    if (_is_not_a_filter($filter)) {
        return $subjects;
    }
    return F\filter($subjects, _build_filter($filter));
}

/**
 * @private
 *
 * @param $filter
 * @return callable
 */
function _build_filter($filters)
{
    if (! is_array($filters)) {
        $filters = [$filters];
    }
    if (_is_associative($filters)) {
        return function ($subject) use ($filters) {
            $reader = _get_reader($subject);
            return F\every($filters, function ($filter, $property) use ($reader, $subject) {
                list($negativeCheck, $filterValue) = _get_filtering($filter);
                $value = $reader($property);
                if (is_array($filter)) {
                    return $negativeCheck
                        ? !in_array($value, $filter)
                        : in_array($value, $filter);
                } else {
                    return $negativeCheck
                        ? $value !== $filterValue
                        : $value === $filterValue;
                }
            });
        };
    } else {
        return function ($subject) use ($filters) {
            return F\every($filters, function ($filter) use ($subject) {
                return $subject === $filter;
            });
        };
    }
}

function _get_filtering($filter)
{
    $negativeCheck = false;
    $filterValue = $filter;
    if ($filter[0] === '-') {
        $negativeCheck = true;
        $filterValue = substr($filter, 1);
    }
    return [$negativeCheck, $filterValue];
}

function _get_reader($subject)
{
    $pa = new PropertyAccessor();
    return P\bind([$pa, 'getValue'], $subject);
}

/**
 * @private
 *
 * @param $filter
 * @return bool
 */
function _is_not_a_filter($filter)
{
    return is_null($filter) || '' === $filter;
}

function _is_associative($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}