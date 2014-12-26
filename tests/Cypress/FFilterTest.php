<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 11/12/14
 * Time: 23.00
 */

namespace Cypress;

use Prophecy\PhpUnit\ProphecyTestCase;

class FFilterTest extends ProphecyTestCase
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
        foreach ($expectedResults as $result) {
            $this->assertContains(
                $result,
                $filteredCollection,
                sprintf(
                    'The collection should contains %s, but it is not. It contains: %s',
                    $result,
                    implode(', ', iterator_to_array($filteredCollection))
                )
            );
        }
        foreach ($notExpectedResults as $notExpectedResult) {
            $this->assertNotContains(
                $notExpectedResult,
                $filteredCollection,
                sprintf(
                    'The collection should NOT contains %s, but it is. It contains: %s',
                    $notExpectedResult,
                    implode(', ', iterator_to_array($filteredCollection))
                )
            );
        }
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
