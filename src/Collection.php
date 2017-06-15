<?php

namespace Softbox\Support;

use JsonSerializable;
use Traversable;

class Collection implements JsonSerializable
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
}
