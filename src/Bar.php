<?php 

namespace Acme;

class Bar
{
    public function get()
    {
        foreach (range(1, 9999) as $value){
            if (true) {
                foreach (range(1, 9999) as $otherValue) {
                    echo $otherValue;
                }
            }

            if ($value % 2 === 0) {
                foreach (range(1, 10) as $int) {
                    if ($value % 2 === 0) {
                        foreach (range(1, 10) as $newValue) {
                            echo $newValue;
                        }
                    }
                }
            }
        }

        if ($newValue === 10) {
            return 'foo';
        }

        return 'bar';
    }

    public function set(int $x): int{
        $z = $x * 2;

        return $z;
    
    }

}
