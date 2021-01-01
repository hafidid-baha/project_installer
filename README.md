### Install

```
composer require hafid/project_installer
```

### Usage

Register Package ServiceProvider
open `config/app.php` file and add line below to providers array

```
\hafid\project_installer\Providers\ProjectInstallerProvider::class,
```

publish all the assets and files

```
php artisan vendor:publish --tag=all
```

Register Package MiddleWare
open `App\Http\Kernel` file and add the line below to the `$routeMiddleware` array

```
'installer' => \App\Http\Middleware\IsInstalled::class,
```

### Configuration

inside the `config\installer.php` you can add and customize your configuration

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

Now your package is ready to be used

```
navigate to you_project_url/install to install your application
```
