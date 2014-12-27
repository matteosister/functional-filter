<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 27/12/14
 * Time: 16.07
 */

namespace Cypress;

use Prophecy\PhpUnit\ProphecyTestCase;

abstract class FunctionalFilterTestCase extends ProphecyTestCase
{
    /**
     * @param array $expectedResults
     * @param \Traversable $collection
     */
    protected function checkContains(array $expectedResults, \Traversable $collection)
    {
        foreach ($expectedResults as $result) {
            $this->assertContains(
                $result,
                $collection,
                sprintf(
                    'The collection should contains %s, but it is not. It contains: %s',
                    $result,
                    implode(', ', iterator_to_array($collection))
                )
            );
        }
    }

    /**
     * @param array $notExpectedResults
     * @param \Traversable $collection
     */
    protected function checkNotContains(array $notExpectedResults, \Traversable $collection)
    {
        foreach ($notExpectedResults as $notExpectedResult) {
            $this->assertNotContains(
                $notExpectedResult,
                $collection,
                sprintf(
                    'The collection should NOT contains %s, but it is. It contains: %s',
                    $notExpectedResult,
                    implode(', ', iterator_to_array($collection))
                )
            );
        }
    }
}