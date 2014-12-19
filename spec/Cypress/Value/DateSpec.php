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
        $this->isLessCheck()->shouldBe(true);
    }

    function it_defaults_to_equal_check()
    {
        $this->isLessCheck()->shouldBe(true);
    }

    function it_throws_with_an_empty_string()
    {
        $this->shouldThrow('InvalidArgumentException')->during('fromString', array(''));
    }

    function it_throws_with_a_date_with_wrong_formatting()
    {
        $this->shouldThrow('InvalidArgumentException')->during('fromString', array('<2012-1-02'));
    }

    function it_throws_with_a_date_with_wrong_date()
    {
        $this->shouldThrow('InvalidArgumentException')->during('fromString', array('<2012-13-02'));
    }

    function it_should_do_a_lesser_check()
    {
        $this::fromString('<2012-10-02')->isLessCheck()->shouldBe(true);
        $this::fromString('<2012-10-02')->isEqualCheck()->shouldBe(false);
    }

    function it_should_do_a_lesser_equal_check()
    {
        $this::fromString('<=2012-10-02')->isLessCheck()->shouldBe(true);
        $this::fromString('<=2012-10-02')->isEqualCheck()->shouldBe(true);
    }

    function it_should_do_a_greater_check()
    {
        $this::fromString('>2012-10-02')->isLessCheck()->shouldBe(false);
        $this::fromString('>2012-10-02')->isEqualCheck()->shouldBe(false);
    }

    function it_should_do_a_greater_equal_check()
    {
        $this::fromString('>=2012-10-02')->isLessCheck()->shouldBe(false);
        $this::fromString('>=2012-10-02')->isEqualCheck()->shouldBe(true);
    }

    function it_should_fail_if_no_sign_is_given()
    {
        $this->shouldThrow('Assert\InvalidArgumentException')->during('fromString', array('2012-10-02'));
    }
}
