<?php

use Acme\Sample;
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    public function testFoo()
    {
        $sample = new Sample();
        $this->assertEquals(1, $sample->foo(1));
    }

    public function testBar()
    {
        $sample = new Sample();
        $this->assertEquals('test', $sample->bar());
    }
}
