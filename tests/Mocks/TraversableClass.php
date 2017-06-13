<?php
namespace Softbox\Support\Tests\Mocks;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class TraversableClass implements IteratorAggregate {
    public $property1 = 1;
    public $property2 = 2;
    public $property3 = 3;


    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator() {
        return new ArrayIterator($this);
    }
}
