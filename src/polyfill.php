<?php

// Provides some PHP "polyfill" methods to work together nicely with php 5.5 or lesse

if (!interface_exists('JsonSerializable')) {
    interface JsonSerializable {
        /**
         * Implements how the array should be serialized.
         *
         * @return mixed
         */
        public function jsonSerialize();
    }
}
