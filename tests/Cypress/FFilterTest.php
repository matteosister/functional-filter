<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 11/12/14
 * Time: 23.00
 */

namespace Cypress;

class FFilterTest extends FunctionalFilterTestCase
{
    /**
     * @dataProvider provider_filter_simple
     *
     * @param $collection
     * @param $filter
     * @param $expectedResults
     * @param array $notExpectedResults
     */
    public function test_filter_simple($collection, $filter, $expectedResults, $notExpectedResults = [])
    {
        $filteredCollection = FFilter::init($collection)->filter($filter);
        if (is_scalar($expectedResults)) {
            $expectedResults = [$expectedResults];
        }
        if (is_scalar($notExpectedResults)) {
            $notExpectedResults = [$notExpectedResults];
        }
        $this->checkContains($expectedResults, $filteredCollection);
        $this->checkNotContains($notExpectedResults, $filteredCollection);
    }

    public function provider_filter_simple()
    {
        return [
            [['a', 'b', 'c'], 'a', 'a'],
            [['a', 'b', 'c'], 'b', 'b'],
            [['a', 'b', 'c'], '-b', ['a', 'c'], ['b']],
        ];
    }
}
