<?php

namespace spec\Cypress;

use PhpCollection\Sequence;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FFilterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\FFilter');
    }

    function it_has_a_all_method()
    {
        $this->all()->shouldBeLike(new Sequence());
    }

    function it_is_constructed_with_a_sequence()
    {
        $this->beConstructedWith(new Sequence());
        $this->all()->beAnInstanceOf('PhpCollection\Sequence');
    }

    function it_has_a_filter_method()
    {
        $this->filter()->shouldBeLike(new Sequence());
    }

    function it_filters_a_collection_with_one_filter_matching()
    {
        $this->beConstructedWith(array('a'));
        $this->filter('a')->shouldContain('a');
    }

    function it_filters_a_collection_with_none_filter_matching()
    {
        $this->beConstructedWith(array('a'));
        $this->filter('b')->shouldBeEmpty();
    }

    function getMatchers()
    {
        return array(
            'contain' => function(Sequence $subject, $key) {
                return $subject->contains($key);
            }
        );
    }
}
