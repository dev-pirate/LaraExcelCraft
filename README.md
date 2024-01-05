# LaraExcelCraft

Foobar is a Python library for dealing with word pluralization.

## Install via composer

Run the following command to pull in the latest version:

```bash
composer require dev-pirate/lara-excel-craft
```

## Publish the config

Run the following command to publish the package config file:

```bash
php artisan vendor:publish --provider="DevPirate\LaraExcelCraft\Providers\LaraExcelCraftProvider"
```

You should now have a config/lara-excel-craft.php file that allows you to configure the basics of this package.

## Add Routes

Add this code inside your route file:

```bash
Route::middleware([
    'api',
    \Fruitcake\Cors\HandleCors::class,
])->group(function() {
    LaraExcelCraft::routes();
});

// \Fruitcake\Cors\HandleCors middleware are required here to manage cors
```

## Custom Excel Import
Before continuing, make sure you have installed the package as per the installation instructions for Laravel.

### Update your User model
Firstly you need to implement the DevPirate\LaraExcelCraft\Interfaces\ImportableInterface interface on your model, which require a custom data importing logic, you implement the 2 methods importDataFromExcel(array $data) and getImportableFields().

The example below should give you an idea of how this could look. Obviously you should make any changes, as necessary, to suit your own needs.

```php
<?php

namespace App\Models;

use Carbon\Carbon;
use DevPirate\LaraExcelCraft\Interfaces\ImportableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model implements ImportableInterface
{
    use HasFactory;

    protected $fillable = [
        'orderDate',
        'region',
        'rep',
        'item',
        'unit',
        'total',
        'created_at',
        'updated_at',
    ];

    public static function importDataFromExcel(array $data)
    {
        $data = array_map(function ($item) {
            return [
                ...$item,
                'total' => floatval($item['total'] ?? 0),
                'unit' => intval($item['unit'] ?? 0),
                'orderDate' => $item['orderDate'] ? Carbon::createFromFormat('m/d/y', trim($item['orderDate'])): null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $data);
        self::insert($data);
    }

    public static function getImportableFields()
    {
        return [
            'orderDate',
            'region',
            'rep',
            'item',
            'unit',
            'total'
        ];
    }
}

```

## Config File

Let's review some of the options in the config/lara-excel-craft.php file that we published earlier.

First up is:
```php
<?php

return [
    // storage disk name where the uploaded temp excel files are going to be stored
    'fileTempDisk' =>  'local',
     // path where your application models classes are stored
    'models_path' => app_path('Models'),
    // route name where you want the application to redirect you after importing the data with excel sheet
    'redirectTo' => 'home'
    // other configuration parameters
];
```
.

## License

[MIT](https://choosealicense.com/licenses/mit/)
