<?php

namespace Softbox\Support\Tests;

use PHPUnit\Framework\TestCase;
use Softbox\Support\Collection;
use Softbox\Support\CollectionHelper;
use Softbox\Support\Tests\Mocks\JsonSerializableClass;
use Softbox\Support\Tests\Mocks\TraversableClass;

class CollectionTest extends TestCase
{
    public function testCreationFromCollection()
    {
        $data                     = array(1, 2, 3);
        $collectionFromArray      = new Collection($data);
        $collectionFromCollection = new Collection($collectionFromArray);

        $this->assertInstanceOf('Softbox\Support\Collection', $collectionFromCollection);
        $this->assertEquals(array(1, 2, 3), $collectionFromCollection->all());
    }

    public function testCreationFromJsonSerializable()
    {
        $jsonSerializableData = new JsonSerializableClass(array(1, 2, 3, 4));
        $collection           = new Collection($jsonSerializableData);

        $this->assertInstanceOf('Softbox\Support\Collection', $collection);
        $this->assertEquals(array(1, 2, 3, 4), $collection->all());
    }

    public function testCreationFromTraversable()
    {
        $traversable = new TraversableClass();
        $collection  = new Collection($traversable);

        $this->assertInstanceOf('Softbox\Support\Collection', $collection);
        $this->assertTrue(is_array($collection->all()));
        $this->assertArrayHasKey('property1', $collection->all());
        $this->assertArrayHasKey('property2', $collection->all());
        $this->assertArrayHasKey('property3', $collection->all());
    }

    public function testCreationFromObject()
    {
        $collection = new Collection(new \stdClass());
        $this->assertInstanceOf('Softbox\Support\Collection', $collection);
    }

    public function testCreationFromLiteral()
    {
        $collection = new Collection("william");
        $this->assertInstanceOf('Softbox\Support\Collection', $collection);
        $this->assertEquals(array("william"), $collection->all());
    }

    /**
     * @test
     */
    public function testMap()
    {
        $data          = array(1, 2, 3, 4);
        $collection    = new Collection($data);
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
        $data       = array(1, 2, 3, 4);
        $collection = new Collection($data);

        $filteredCollection = $collection->filter(function ($item) {
            return $item % 2 == 1;
        }, false);
        $odds               = $filteredCollection->all();

        $this->assertTrue(is_array($odds));
        $this->assertTrue(!empty($odds));
        $this->assertEquals(array(1, 3), $odds);
        $this->assertNotEquals(array(1, 3), $collection->all());
        $this->assertNotEquals($odds, $data);
    }

    public function testReduce()
    {
        $data       = array(1, 2, 3, 4, 5, 6);
        $collection = new Collection($data);

        $reducedData = $collection
            ->filter(function ($item) {
                return $item % 2 == 0;
            })
            ->map(function ($item) {
                return $item * 2;
            })
            ->reduce(function ($acc, $other) {
                return $acc + $other;
            })
        ;
        $this->assertEquals(24, $reducedData);
        $this->assertInstanceOf('Softbox\Support\Collection', $collection);
        $this->assertEquals(array(1, 2, 3, 4, 5, 6), $collection->all());
    }

    public function testChunk()
    {
        $data       = array(1, 2, 3, 4, 5);
        $collection = new Collection($data);
        $chunks     = $collection->chunk(3)->all();

        $this->assertEquals(2, count($chunks));
        $this->assertEquals(array(1, 2, 3), $chunks[0]);
        $this->assertEquals(array(4, 5), $chunks[1]);
        $this->assertEquals(array(1, 2, 3, 4, 5), $collection->all());
    }

    public function testCountable()
    {
        $data = array(
            array(1, 2, 3, 4, 5),
            array(1, 2, 3),
            array(),
            (object) array(
                'name' => 'William',
                'age'  => 28,
            ),
        );

        /** @var Collection[] $collections */
        $collections = array_map(function ($data) {
            return new Collection($data);
        }, $data);

        foreach ($collections as $key => $collection) {
            $originalData = (array) $data[$key];
            $this->assertInstanceOf('Softbox\Support\Collection', $collection);
            $this->assertEquals($originalData, $collection->all());
            $this->assertEquals(count($originalData), $collection->count());
            $this->assertEquals(count($originalData), count($collection));
        }
    }
}
