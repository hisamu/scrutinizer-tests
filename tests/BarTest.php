<?php

use Acme\Bar;
use PHPUnit\Framework\TestCase;

class BarTest extends TestCase
{
    public function testSet()
    {
        $bar = new Bar();

        $this->assertEquals(4, $bar->set(2));
    }
}
