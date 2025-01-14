# VoyagerDataTransport
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fvanchao0519%2FVoyagerDataTransport.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fvanchao0519%2FVoyagerDataTransport?ref=badge_shield)

A command line tools to generate the Controller and the View files 
which can import data to database and export data to the file which extension included excel, csv and pdf.
<br>
Notice that the package is based on <a href="https://voyager.devdojo.com/">Voyager</a>.
<br>
## Before install
You must confirm that laravel project created and the voyager package installed before.
<br>
## How to install the VoyagerDataTransport
```php
composer require vanchao0519/voyager-data-transport
```
## How to use
The fastest way:
```php
php artisan voyager:data:transport <data-tabel-name>
```
You can also used the single command which you want:
- Create import controller file
```php
php artisan voyager:data:import:controller <data-tabel-name>
```
- Create export controller file
```php
php artisan voyager:data:export:controller <data-tabel-name>
```
- Create browse view file
```php
php artisan voyager:data:transport:browse:view <data-tabel-name>
```
- Create import data view file
```php
php artisan voyager:data:transport:import-data:view <data-tabel-name>
```
- Create voyager data transport permission detail config file
```php
php artisan voyager:data:transport:permission:detail:config <data-tabel-name>
```
- Create voyager data transport route detail config file
```php
php artisan voyager:data:transport:route:detail:config <data-tabel-name>
```
- Publish config files to app/VoyagerDataTransport/config/permissions and app/VoyagerDataTransport/config/route folder
```php
php artisan voyager:data:transport:publish:config
```
## More
Here is a <a href="https://github.com/vanchao0519/VoyagerDataTransportDemo">demonstrate</a> more details after you execute the command line of this project

## License
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fvanchao0519%2FVoyagerDataTransport.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fvanchao0519%2FVoyagerDataTransport?ref=badge_large)