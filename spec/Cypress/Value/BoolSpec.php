<?php

namespace spec\Cypress\Value;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BoolSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\Value\Bool');
    }

    function it_defaults_to_positive_check()
    {
        $this->isPositiveCheck()->shouldBe(true);
    }

    function it_defaults_to_strict()
    {
        $this->isStrict()->shouldBe(true);
    }

    function it_should_be_constructed_with_a_static_method()
    {
        $this->shouldThrow('Assert\InvalidArgumentException')->during('fromString', array(1));
    }

    function it_should_default_with_an_empty_string()
    {
        $this::fromString('')->getValue()->shouldBe('');
        $this::fromString('')->isPositiveCheck()->shouldBe(true);
        $this::fromString('')->isStrict()->shouldBe(true);
    }

    function it_should_do_a_negative_check()
    {
        $this::fromString('-test')->getValue()->shouldBe('test');
        $this::fromString('-test')->isPositiveCheck()->shouldBe(false);
        $this::fromString('-test')->isStrict()->shouldBe(true);
    }

    function it_should_do_a_not_strict_check()
    {
        $this::fromString('test~')->getValue()->shouldBe('test');
        $this::fromString('test~')->isPositiveCheck()->shouldBe(true);
        $this::fromString('test~')->isStrict()->shouldBe(false);
    }

    function it_should_do_a_negative_AND_not_strict_check()
    {
        $this::fromString('-test~')->getValue()->shouldBe('test');
        $this::fromString('-test~')->isPositiveCheck()->shouldBe(false);
        $this::fromString('-test~')->isStrict()->shouldBe(false);
    }
}
