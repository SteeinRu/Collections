# Steein Collections
Collection library for working with data.

## Using Collection

### Declaring the main class

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

$collection = new Collection($array);
```

### Available methods and implementation methods
