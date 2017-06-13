<?php
namespace Softbox\Support\Tests;

use JsonSerializable;

require_once __DIR__ . '/JsonSerializable.php';

class JsonSerializableClass implements JsonSerializable {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function jsonSerialize() {
        return $this->data;
    }
}
