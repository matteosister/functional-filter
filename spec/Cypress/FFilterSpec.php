<?php

namespace spec\Cypress;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FFilterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Cypress\FFilter');
    }
}
