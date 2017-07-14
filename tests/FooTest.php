<?php

use Acme\Foo;
use PHPUnit\Framework\TestCase;

class FooTest extends TestCase
{
    public function testBazz()
    {
        $foo = new Foo();
        $this->assertEquals(1, $foo->bazz(1));
    }
}
