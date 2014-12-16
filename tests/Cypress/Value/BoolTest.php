<?php
/**
 * Created by PhpStorm.
 * User: matteo
 * Date: 11/12/14
 * Time: 23.13
 */

namespace Cypress\Value;

use Assert\InvalidArgumentException;
use Prophecy\PhpUnit\ProphecyTestCase;

class BoolTest extends ProphecyTestCase
{
    public function test_instance_with_string()
    {
        $b = Bool::fromString('a');
        $this->assertTrue($b->isPositiveCheck());
        $this->assertTrue($b->isStrict());
        $this->assertEquals('a', $b->getValue());
    }

    public function test_instance_with_array()
    {
        $b = new Bool(array(1,2,3));
        $this->assertTrue($b->isPositiveCheck());
        $this->assertTrue($b->isStrict());
        $this->assertEquals(array(1,2,3), $b->getValue());
    }

    public function test_instance_with_negative_string()
    {
        $b = Bool::fromString('-test');
        $this->assertFalse($b->isPositiveCheck());
        $this->assertTrue($b->isStrict());
        $this->assertEquals('test', $b->getValue());
    }

    public function test_strictness_with_operator()
    {
        $b = Bool::fromString('-test~');
        $this->assertFalse($b->isPositiveCheck());
        $this->assertFalse($b->isStrict());
        $this->assertEquals('test', $b->getValue());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_from_string_with_wrong_type()
    {
        Bool::fromString(true);
    }

    public function test_from_string_with_blank_string()
    {
        $b = Bool::fromString('');
        $this->assertTrue($b->isPositiveCheck());
        $this->assertTrue($b->isStrict());
        $this->assertEquals('', $b->getValue());
    }
}
