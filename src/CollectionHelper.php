<?php
namespace Softbox\Support;

use BadMethodCallException;

class CollectionHelper
{
    private static $transformerCache = array();

    /**
     * Simply apply a map over an array.
     * @param array $data
     * @param callable $callback
     * @param bool $keepKeys
     * @return array
     */
    public static function map(array $data, $callback, $keepKeys = true)
    {
        static::isCallableOrThrowException($callback);

        $tmp = array();

        foreach ($data as $key => $value) {
            $tmp[$key] = $callback($value, $key);
        }

        if (!$keepKeys) {
            return array_values($tmp);
        }

        return $tmp;
    }

    /**
     * Apply a filter function over the given array and return only the items where the filter function return true.
     * @param array $data
     * @param $callback
     * @param bool $keepKeys
     * @return array
     */
    public static function filter(array $data, $callback, $keepKeys = true)
    {
        static::isCallableOrThrowException($callback);

        $tmp = array();

        foreach ($data as $key => $value) {
            if (!!$callback($value, $key)) {
                $tmp[$key] = $value;
            }
        }

        if (!$keepKeys) {
            return array_values($tmp);
        }

        return $tmp;
    }

    /**
     * Reduces an array with the given callback function.
     *
     * @param array $data
     * @param $callback
     * @param null $initialValue
     * @return mixed|null
     */
    public static function reduce(array $data, $callback, $initialValue = null)
    {
        static::isCallableOrThrowException($callback);

        $reduced = $initialValue !== null ? $initialValue : array_shift($data);

        foreach ($data as $key => $value) {
            $reduced = $callback($reduced, $value, $key);
        }

        return $reduced;
    }

    /**
     * Transform an array changing the the keys by the given key-value pair.
     *
     * @param array $data
     * @param array $changes
     * @param string $delimiter
     * @return mixed
     */
    public static function transform(array $data, array $changes, $delimiter = '.')
    {
        $transformer = static::getTransformer($changes, $delimiter);

        return $transformer->transform($data);
    }

    /**
     * Applies the same transform filter but over a list.
     *
     * @param array $data
     * @param array $changes
     * @param string $delimiter
     * @return mixed
     */
    public static function transformArray(array $data, array $changes, $delimiter = '.')
    {
        $transformer = static::getTransformer($changes, $delimiter);

        return $transformer->transformArray($data);
    }

    /**
     * Get an instance of the transformer based on the given parameters.
     *
     * @param $changes
     * @param $delimiter
     * @return mixed
     */
    private static function getTransformer($changes, $delimiter)
    {
        $key = md5(json_encode($changes)) . $delimiter;
        if (!array_key_exists($key, static::$transformerCache)) {
            static::$transformerCache[$key] = new Transformer($changes, $delimiter);
        }

        return static::$transformerCache[$key];
    }

    /**
     * Helper function to detect the the given callback is callable.
     *
     * @param $param
     */
    private static function isCallableOrThrowException($param)
    {
        if (!is_callable($param)) {
            throw new BadMethodCallException('Should inform a callable parameter');
        }
    }
}
