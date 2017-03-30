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

The magic method for creating and retrieving a new item for the collection
```php
$collection->new_array = 'value';
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
