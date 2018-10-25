# [WIP] Multilingual Laravel :globe_with_meridians:</h1>


## Introduction
Multilingual is a localization package for Laravel apps. It has built-in methods to make localization simple. It aims to give you developing speed and not to worry about locales.

- Handling redirects easily (domain or path based)
- Extended router class for localized routes
- @forEachLocale Blade directive
- Highly customizable


## Installation
You may use Composer to install Multilingual into your Laravel project:
```sh 
composer require ozanakman/laravel-multilingual
```
> **Note:** Multilingual requires Laravel 5.7.0+.

After installing Multilingual, publish its assets using the `multilingual:install` Artisan command. It will migrate `locales` and `translations` tables.

```sh
php artisan multilingual:install
```

After publishing Multilingual's assets, its primary configuration file will be located at `config/multilingual.php`.

## Configuration

There are two options in the config file. 

`pattern` is used to handle redirects and how should system treat to urls. Pattern can *only* be `domain` or `path`.

when you select `domain` as the pattern. Localized url would be something like this: 
`en.domain.com` or `tr.domain.com`.

when you select `path` as the pattern. Localized url would be something like this: 
`domain.com/en` or `domain.com/tr`.

Other options is just for customizing *middleware*. You can either use `OzanAkman\Multilingual\Middleware\Localize` which is handling redirects or create your own.

### Content of the published config file
```php
return [
    /*
     * How we should treat urls to get the selected language?
     * http://{domain}.site.com
     * http://site.com/{path}
     *
     * Supported: "domain", "path"
     */
    'pattern' => 'path',

    /**
     * Localization middleware to handle user redirects.
     */
    'middleware' => OzanAkman\Multilingual\Middleware\Localize::class,
];
```
 
 ## Localized Routes
 
 There is a mixing for localized routes that you can use. `Route::locale` creates localized urls for each locale. In the example below, User will see `welcome` view by visiting `domain.com/en/home` or `domain.com/tr/home`
 
 ```php 
 Route::locale(function () {
     Route::get('/home', function () {
         return view('welcome');
     });
 });
 ```
 
 You may also add attributes to `Route::locale` as same as `Route::group` method 
 
  ```php  
  Route::locale(['middleware' => 'auth'], function () {
      Route::get('/invoice/{invoiceId}', 'InvoiceController@show');
  });
  ```
  
  After all, it's just a shortcut.
  
## Blade Directive

You can easily handle locale based components like locale selection dropdown or multilingual content editor, etc.. 

You can use this directive as much as you want. Locales are cached behind scenes. So, no need to worry.

```blade
@forEachLocale($item)
{{ $item->code }}
{{ $item->name }}
{{ $item->native_name }}
{{ $item->enabled }}
{{ $item->default }}
@endForEachLocale

```

## Models

`Locale` and `Translation` model files available under `OzanAkman\Multilingual\Models` namespace. 

# Locales

- You can add a new locale by calling `Artisan` command:

```bash 
php artisan multilingual:add {code} {name} {native name}
```
```bash 
php artisan multilingual:add tr Turkish Türkçe
```

- Removing a language
```bash 
php artisan multilingual:remove tr
```
> Be careful, this command will delete all translations that belongs to the locale.


**Manually,** you can use Locale model to add/edit/remove a locale. 

## Translations

Add `HasMultilingualContent` trait to add your model files to go on.

- Translating model to other locales:
```php
$model->translate('en', [
    'column' => 'translated_version'
]);
```

- Removing translation:
```php 
$model->removeTranslation('en');
```

* Handling translation slugs is so easy. You just need to add `slugSource()` method to your main model. 

```php
class ExampleModel extends Model
{
    public function slugSource()
    {
        // Column
        return 'title';
    }
}
```

And now when you add a translation Multilingual will generate `slug` from `title` column by `cocur/slugify` package.

## Testing
Multilingual uses `Orchestra\Testbench` to run tests.

```bash
vendor/bin/phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

