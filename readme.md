[![Build Status](https://travis-ci.org/SoftboxLab/php-collection-helper.svg?branch=master)](https://travis-ci.org/SoftboxLab/php-collection-helper)
[![codecov](https://codecov.io/gh/SoftboxLab/php-collection-helper/branch/master/graph/badge.svg)](https://codecov.io/gh/SoftboxLab/php-collection-helper)

# Simple PHP Collection and array helper
Simple collection class to work with arrays on object-oriented paradigm and some array (collection) helpers with standardization of the parameters (data first, callback later, like *javascript*).

## Collection
In this definition, our collection is *Immutable*, which means that *every* operation should return a new collection, keeping the original collection intact.

```php
$collection = new Collection([1, 2, 3, 4]);
$newCollection = $collection->map(function ($item) {
    return $item * 2;
});

print_r($newCollection->all()); // [2, 4, 6, 8]
print_r($collection->all()); // [1, 2, 3, 4]
```

## Collection helper
This is the base support to the Collection class. All the methods are implemented here.

### List of available methods
- map($data, $callback ($value, $key));
  - Map all the items of data with the provided callback. The callback will receive both value's and key's of the array.
- filter($data, $callback($value, $key), $keepKeys);
  - Filter the given array with the provided callback. Only the items when the callback return's true will be returned on the filtered data. If false is passed to *keepKeys* parameter, the keys will be reseted.
- reduce($data, $callback($value, $key), $initialValue);
  - Reduce's the given data with the given callback.
- transform($data, $changes, $delimiter);
  - Transform the keys (change) of the given `$data` array with the `$changes` array. To navigate along the array, use the Laravel's dot syntax. If `$delimiter` provided, it changed the Laravel's dot syntax separator.
- transformArray($data, $changed, $delimiter);
  - Same as transform, but should be used on a list, which will apply on every item. Same as map with transform.

## Transformer class
Simple array transformer class, which convert all the keys by new ones. Something like a `from-to` where the all the keys of the given array will be replaced the by the value of the given replaced array when the key matches.

Example:

```php
$transformer = new Transformer(['namae' => 'nombre']);
$data = ['namae' => 'William'];
$transformedData = $transformer->transform($data);

print_r($transformedData); // ['nombre' => 'William'];
```

## Contributing
Feel free to make any pull request you judge necessary!
