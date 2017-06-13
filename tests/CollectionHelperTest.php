<?php

namespace Softbox\Support\Tests;

use PHPUnit\Framework\TestCase;
use Softbox\Support\CollectionHelper;

class CollectionHelperTest extends TestCase
{
    /**
     * @test
     */
    public function testMap()
    {
        $array = array(1, 2, 3, 4, 5);
        $mappedArray = CollectionHelper::map($array, function ($item) {
            return $item * 2;
        });

        foreach ($array as $key => $value) {
            $this->assertEquals($value * 2, $mappedArray[$key]);
        }
    }

    public function testFilter()
    {
        $array = array(1, 2, 3, 4, 5);
        $evens = CollectionHelper::filter($array, function ($item) {
            return $item % 2 === 0;
        });

        $this->assertEquals(array(1 => 2, 3 => 4), $evens);
    }

    public function testReduce()
    {
        $array = array(1, 2, 3, 4, 5, 6);
        $reducedArray = CollectionHelper::reduce($array, function ($item, $other) {
            return $item + $other;
        });
        $this->assertEquals(21, $reducedArray);
    }
}
