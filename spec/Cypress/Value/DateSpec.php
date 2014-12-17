<?php

namespace spec\Cypress\Value;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('2012-21-02');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\Value\Date');
    }

    function it_defaults_to_asc_order()
    {
        $this->isAscendingOrder()->shouldBe(true);
    }

    function it_defaults_to_equal_check()
    {
        $this->isEqualCheck()->shouldBe(true);
    }

    function it_throws_with_an_empty_string()
    {
        $this->shouldThrow('InvalidArgumentException')->during('fromString', array(''));
    }

    function it_throws_with_a_date_with_wrong_formatting()
    {
        $this->shouldThrow('InvalidArgumentException')->during('fromString', array('2012-13-02'));
    }
}
