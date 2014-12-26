<?php

namespace spec\Cypress;

use PhpCollection\AbstractCollection;
use PhpCollection\Sequence;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FFilterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\FFilter');
    }

    function it_is_initializable_with_a_static_method()
    {
        $this->init([])->shouldBeAnInstanceOf('Cypress\FFilter');
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
        $this->beConstructedWith(['a']);
        $this->filter('a')->shouldContains('a');
    }

    function it_filters_a_collection_with_none_filter_matching()
    {
        $this->beConstructedWith(['a']);
        $this->filter('b')->shouldBeEmpty();
    }

    function it_filters_a_map_with_one_filter_matching()
    {
        $this->beConstructedWith([['a' => 'a'], ['a' => 'b']]);
        $this->filter(['[a]' => 'a'])->shouldContains(['a' => 'a']);
        $this->filter(['[a]' => 'a'])->shouldNotContains(['a' => 'b']);
    }

    function getMatchers()
    {
        return [
            'contains' => function(AbstractCollection $subject, $value) {
                return $subject->contains($value);
            },
            'notContains' => function(AbstractCollection $subject, $value) {
                return !$subject->contains($value);
            }
        ];
    }
}
