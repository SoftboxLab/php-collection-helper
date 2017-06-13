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
     * @return array
     */
    public static function map(array $data, $callback)
    {
        static::isCallableOrThrowException($callback);

        $tmp = array();

        foreach ($data as $key => $value) {
            $tmp[] = $callback($value, $key);
        }

        return $tmp;
    }

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

    public static function reduce(array $data, $callback, $initialValue = null)
    {
        static::isCallableOrThrowException($callback);

        $reduced = $initialValue !== null ? $initialValue : array_shift($data);

        foreach ($data as $key => $value) {
            $reduced = $callback($reduced, $value, $key);
        }

        return $reduced;
    }

    private static function isCallableOrThrowException($param)
    {
        if (!is_callable($param)) {
            throw new BadMethodCallException('Should inform a callable parameter');
        }
    }

    public static function transform(array $data, array $changes, $delimiter = '.')
    {
        $transformer = static::getTransformer($changes, $delimiter);

        return $transformer->transform($data);
    }

    public static function transformArray(array $data, array $changes, $delimiter = '.')
    {
        $transformer = static::getTransformer($changes, $delimiter);

        return $transformer->transformArray($data);
    }

    private static function getTransformer($changes, $delimiter)
    {
        $key = md5(json_encode($changes)) . $delimiter;
        if (!array_key_exists($key, static::$transformerCache)) {
            static::$transformerCache[$key] = new Transformer($changes, $delimiter);
        }

        return static::$transformerCache[$key];
    }
}
