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
        'car_bwm    =>  'Bmw'
    ]
];

//Default
$collection = new Collection($array);

Or Static implementation
$collection = Collection::instance($array);

```
