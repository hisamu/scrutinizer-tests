<?php

namespace Acme;

class Foo {
    function test ($foo)
    {
        if($foo =='bar') {
            return TRUE;
        }

        return false;
    }

    public function bar() : void {
        echo 'zed';
    }
}
