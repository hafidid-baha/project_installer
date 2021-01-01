### Install

```
composer require hafid/project_installer
```

### Usage

publish all the assets and files

```
php artisan vendor:publish
```

Register Package ServiceProvider
open `config/app.php` file and add line below to providers array

```
php artisan vendor:publish
```

Register Package MiddleWare
open `App\Http\Kernel` file and add the line below to the `$routeMiddleware` array

```
'installer' => \App\Http\Middleware\IsInstalled::class,
```

### Configuration

```php
return [
    "min_php_version" => "7.2.5",
    // add any required extension for you application
    "required_extensions" => [
        'openssl',
        'pdo',
        'mbstring',
        'xml',
        'ctype',
        'gd',
        'tokenizer',
        'JSON',
        'bcmath',
        'exif',
        'cURL',
        'fileinfo',
    ],
    // add any folder to check his write and open permisions
    "required_permissions" => [
        'storage'           => '0777',
        'storage/app'       => '0777',
        'storage/framework' => '0777',
        'storage/logs'      => '0777',
        'bootstrap/cache'   => '0777',
    ],
];
```
