<?php

if (!interface_exists('Serializable')) {
    interface Serializable {
        public function serialize();

        public function unserialize($serialized);
    }
}
