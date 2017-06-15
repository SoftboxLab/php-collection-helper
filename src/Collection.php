<?php

namespace Softbox\Support;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

class Collection implements JsonSerializable, ArrayAccess, Countable, IteratorAggregate, Jsonable, Arrayable
{
    private $data = array();

    public function __construct($data)
    {
        $this->data = $this->arrayfy($data);
    }

    /**
     * Applies map over the Collection. Returns a new one.
     *
     * @param callable $callback
     * @return static
     */
    public function map($callback)
    {
        return new static(CollectionHelper::map($this->data, $callback));
    }

    /**
     * Applies filter over the Collection. Returns a new one.
     *
     * @param callable $callback
     * @param bool $keepKeys
     * @return static
     */
    public function filter($callback, $keepKeys = true)
    {
        return new static(CollectionHelper::filter($this->data, $callback, $keepKeys));
    }

    /**
     * Applies reduce over the Collection. Returns a new one.
     *
     * @param $callback
     * @return static
     */
    public function reduce($callback)
    {
        return CollectionHelper::reduce($this->data, $callback);
    }

    /**
     * Applies chunk over the Collection. Returns a new one.
     *
     * @param $chunkSize
     * @param bool $keepKeys
     * @return static
     */
    public function chunk($chunkSize, $keepKeys = false)
    {
        return new static(CollectionHelper::chunk($this->data, $chunkSize, $keepKeys));
    }

    /**
     * Return the data of the Collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Tries to convert any given data into some type of array somehow.
     *
     * @param $data
     * @return array|mixed
     */
    private function arrayfy($data)
    {
        if (is_array($data)) {
            return $data;
        } elseif ($data instanceof self) {
            return $data->all();
        } elseif ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        } elseif ($data instanceof Traversable) {
            return iterator_to_array($data);
        }

        return (array)$data;
    }

    /**
     * Implements how the array should be serialized.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return array_map(function ($data) {
            if ($data instanceof JsonSerializable) {
                return $data->jsonSerialize();
            }

            return $data;
        }, $this->data);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Convert the collection into an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($data) {
            return $data instanceof Arrayable ? $data->toArray() : $data;
        }, $this->data);
    }

    /**
     * Convert to collection into json string.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
