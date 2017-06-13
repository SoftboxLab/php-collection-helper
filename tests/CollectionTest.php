<?php

namespace Softbox\Support\Tests;

use PHPUnit\Framework\TestCase;
use Softbox\Support\Collection;

class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function testMap()
    {
        $data = array(1, 2, 3, 4);
        $collection = new Collection($data);
        $newCollection = $collection->map(function ($item) {
            return $item * 2;
        });

        $this->assertEquals(array(1, 2, 3, 4), $collection->all());
        $this->assertEquals($data, $collection->all());
        $this->assertEquals(array(2, 4, 6, 8), $newCollection->all());
        $this->assertNotEquals($newCollection, $collection);
        $this->assertNotEquals($newCollection->all(), $collection->all());
    }

    public function testFilter()
    {
        $data = array(1, 2, 3, 4);
        $collection = new Collection($data);

        $filteredCollection = $collection->filter(function ($item) {
            return $item % 2 == 1;
        }, false);
        $odds = $filteredCollection->all();

        $this->assertTrue(is_array($odds));
        $this->assertTrue(!empty($odds));
        $this->assertEquals(array(1, 3), $odds);
        $this->assertNotEquals(array(1, 3), $collection->all());
        $this->assertNotEquals($odds, $data);
    }
}
