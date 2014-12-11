<?php

namespace Cypress;

use PhpCollection\Sequence;

class FFilter
{
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
            return [false, substr($value, 1)];
        }
        return [true, $value];
    }
}
