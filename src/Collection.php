<?php

namespace Softbox\Support;

use JsonSerializable;
use Traversable;

class Collection
{
    private $data = array();

    public function __construct($data)
    {
        $this->data = $this->arrayfy($data);
    }

    public function map($callback)
    {
        return new static(CollectionHelper::map($this->data, $callback));
    }

    public function all()
    {
        return $this->data;
    }

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
}
