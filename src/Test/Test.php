<?php
namespace Strata\Test;

use PHPUnit_Framework_TestCase;

/**
 * A class to contain test cases and run them with shared fixtures
 */
class Test extends PHPUnit_Framework_TestCase
{
    public function testExtendsCorrectObject()
    {
        $this->assertTrue($this instanceof PHPUnit_Framework_TestCase);
    }

}
