# Eloquent Hashids for Laravel 5.x
[![Build Status](https://travis-ci.org/matt-daneshvar/eloquent-hashids.svg?branch=master)](https://travis-ci.org/matt-daneshvar/eloquent-hashids)
![GitHub](https://img.shields.io/github/license/matt-daneshvar/eloquent-hashids.svg)

Automatically persist Hashids on your newly created Eloquent models 
using Ivan Akimov's [Hashids library](https://github.com/ivanakimov/hashids.php).

This can be useful when you need to generate a unique alphanumeric (or any other character) combinations 
to represent your models.

## Installation

Require the package using composer.

```bash
composer require matt-daneshvar/eloquent-hashids
```

## Usage
Add a *nullable* `hashid` column to your database table in your migrations. 
```php
$table->string('hashid')->nullable();
```

Use the `Hashid` trait to automatically generate and persist Hashids for your new models.
Optionally use `HashidRouting` to set your model to use the `hashid` column for 
Laravel's [Route Model Binding](https://laravel.com/docs/routing#route-model-binding). 

```php
class Receipt extends Model
{
    use Hashid, HashidRouting;
}
```

### Customizing Hashid generation
While the package attempts to use sensible defaults to minimize configuration out of the box, 
you're free to adjust the Hashid generation behaviour using static properties on your model definition.
```php
class Receipt extends Model
{
    use Hashid;
    
    /**
     * The column used to store Hashid.
     *
     * @var array
     */
    protected static $hashidColumn = 'hashid';
    
    /**
     * The minimum length of the generated Hashids.
     *
     * @var array
     */
    protected static $hashidMinLength = 8;
    
    /**
     * The whitelist of characters used inside the generated Hashids.
     *
     * @var array
     */
    protected static $hashidChars = 'abcdefghijklmnopqrstuvwxyz1234567890';
    
    /**
     * The salt for generating Hashids.
     *
     * @var array
     */
    protected static $hashidSalt = 'your unique salt';
    
    /**
     * The attribute encoded to generate the Hashid.
     *
     * @var array
     */
    protected static $hashidKey = 'id';
}
```

### Changing the Hashid column
To customize the hashid column, set your own custom `$hashidColumn` value on your model.
```php
class Receipt extends Model
{
    use Hashid;
    
    protected static $hashidColumn = 'uid';
}
```

### Changing the salt
Each model's table name is by default used as the salt for generating Hashids.
With that, models of separate classes that share the same IDs 
(e.g. a `Task` model with ID of 1 and a `Receipt` model also with ID of 1) would each have different Hashids.
You may change this behaviour and override the salt by specifying the `$hashidSlat` on your model.
 ```php
 class Receipt extends Model
 {
     use Hashid;
     
     protected static $hashidSalt = 'salt and pepper';
 }
 ```

### Creating your own Hashids instance
To fully customize the behaviour of the underlying Hashids library, 
you may also define your own `Hashids` instance in your model's boot method. 
Note that your Hashids instance would take precedence over 
all other customizations, and therefore all the rest of the static Hashid properties on your model 
(i.e. `$hashidMinLength`, `$hashidChars`, etc.)
would be ignored once you specify your own `Hashids` instance. 
```php
class Receipt extends Model
{
    public static function boot()
    {
        parent::boot();
    
        static::$hashidsInstance = new Hashids('salt and pepper', 5);
    }
}
```

### Using the HashidRouting trait
A common use case of Hashids with Eloquent models 
is to use short URLs using the generated Hashids as identifiers.

For example you may wish to represent your app's receipts using their Hashid values:
```
https://example.com/receipts/2ov7j3o3
```
instead of their IDs:
```
https://example.com/receipts/4
```

For more convenience this package comes with a `HashidRouting` trait out of the box; once added to your model, 
this trait will change the model's route key name to its corresponding Hashid column,
which would allow you to take advantage of 
Laravel's [Route Model Binding](https://laravel.com/docs/routing#route-model-binding)
and use the Hashid URLs:
```php
Route::get('api/receipts/{receipt}', function (App\Receipt $receipt) {
    return $receipt->total;
});
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
