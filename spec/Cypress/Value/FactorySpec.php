<?php

namespace spec\Cypress\Value;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\Value\Factory');
    }

    function it_creates_a_bool_value_with_an_empty_string()
    {
        $this::create('')->shouldBeAnInstanceOf('Cypress\Value\Bool');
    }

    function it_creates_a_bool_value_with_a_string()
    {
        $this::create('test')->shouldBeAnInstanceOf('Cypress\Value\Bool');
    }
}
