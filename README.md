# Steein Collections
Collection library for working with data.

## Using Collection

```php
use Steein\Common\Collections\Collection;

//Generated default array
$array = [
    'name'      =>  'Shamsudin',
    'project'   =>  'Steein',
    'text'      =>  'default text',

    'Cars'  =>  [
        'car_audi' =>  'Audi',
        'car_bwm'  =>  'Bmw'
    ]
];

//Default
$collection = new Collection($array);

//Or Static implementation
$collection = Collection::instance($array);

```

### Available methods and implementation methods

In this documentation we will describe the most necessary methods, and all the rest, and you can see a lot of them in the code, everything is described there

***

The magic method for creating and retrieving a new item for the collection
```php
$collection->new_array = 'value';
```

**toArray()**

Getting the collections of elements in the Array format.

```php
$collection->toArray()
```

**toJson()**

Getting the collections of elements in the Json format.

```php
$collection->toJson();
```

**toXml()**

Getting the collections of elements in the Json format.

```php
$collection->toXml();
```

**set()**

New item for collection

```php
$collection->set('key', 'value');
```

**get()**

Get the item from the collection by key.

```php
$collection->get('key');
```

**slice()**

Create the main array of the collection.

```php
$collection->slice(1);
```
**nth()**

Create a new collection of each n-th attribute.

```php
$collection->nth(2);
```

**union()**

Union of collections with these elements.

```php
$new_collection = Collection::instance([0 => ['a'], 1 => ['b'], 2 => ['c']]);
$new_collection->union([3 => ['b'], 2 => ['c']]);
```

**combine()**

Create a collection using this collection for keys, and the other for its values.

```php
$new_collection = Collection::instance(['firstname','lastname','age']);
$new_collection->combine(['Shamsudin','Serderov', 22]);
```

**merge()**

Combine the collection with these elements.

```php
$new_collection = Collection::instance(['id_goods' => 100,'price' => 10000, 'instock' => 0]);
$new_collection->merge(['price' => 9550, 'count' => 1]);
```

**isEmpty()**

Determine whether the collection is empty or not.

```php
$collection->isEmpty();
```

**isNotEmpty()**

Determine if the collection is empty.

```php
$collection->isNotEmpty();
```

**has()**

Determine if an element exists in the collection by key.

```php
$collection->has('project');
```

**forget()**

Remove an item from the collection by key.

```php
$collection->forget('name');
```

**archive()**

Archive the collection with one or more arrays.

```php
$new_collection = Collection::instance(['test', 'test1']);
$new_collection->archive([50, 22]);
```

**clear()**

Clearing Attributes in the Collection

```php
$collection->clear();
```

**count()**

Count the number of items in the collection.

```php
$collection->count()
```

**flip()**

We turn the elements into collections.

```php

$flip = $collection->flip();
$flip->all();
```

**values()**

Get values from items in the collection

```php
$collection->values();
```

**keys()**

Get the keys to the attributes of the collection.

```php
$collection->keys();
```

**indexOf()**

We search for a specific value in the collection

```php
$collection->indexOf('Shamsudin');
```

**all()**

Get all attributes

```php
$collection->all();
```
