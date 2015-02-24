<?php

namespace test\Cypress\FunctionalFilter;

use Cypress\FunctionalFilter as FF;

class functionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $subjects
     * @param $filter
     * @param $expected
     *
     * @dataProvider filter_provider
     */
    public function test_filter($subjects, $filter, $expected)
    {
        $this->assertEquals($expected, FF\filter($subjects, $filter));
    }

    public function filter_provider()
    {
        return [
            [['a', 'b'], null, ['a', 'b']],
            [['a', 'b'], '', ['a', 'b']],
            [['a', 'b'], 'a', ['a']],
            [['a', 'b'], 'b', [1 => 'b']],
            [[new SubjectA(), new SubjectB()], ['v' => 'valueA'], [new SubjectA()]],
            [[new SubjectA(), new SubjectB()], ['v' => '-valueA'], [1 => new SubjectB()]],
            [[new SubjectA(), new SubjectB(), new SubjectB()], ['v' => '-valueA'], [1 => new SubjectB(), 2 => new SubjectB()]],
            [[new SubjectA(), new SubjectB(), new SubjectB()], ['v' => 'valueA'], [0 => new SubjectA()]],
            [[new SubjectC(), new SubjectD()], ['v1' => 'A', 'v2' => 'C'], [1 => new SubjectD()]],
            [[new SubjectC(), new SubjectD()], ['v1' => 'A', 'v2' => '-D'], [new SubjectC(), new SubjectD()]],
            [[new SubjectC(), new SubjectD()], ['v1' => 'A', 'v2' => 'B'], [new SubjectC()]],
            [[new SubjectC(), new SubjectD()], ['v2' => ['B', 'C']], [new SubjectC(), new SubjectD()]],
        ];
    }
}

class SubjectA
{
    public $v = 'valueA';
}

class SubjectB
{
    private $v = 'valueB';

    public function getV()
    {
        return $this->v;
    }
}

class SubjectC
{
    public $v1 = 'A';
    public $v2 = 'B';
}

class SubjectD
{
    public $v1 = 'A';
    public $v2 = 'C';
}