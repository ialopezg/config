# Configuration File Manager

PHP based Configuration file manager

## Installation

```shell script
composer require ialopezg/config
```

## Features

* Exceptions handling.
* PHP configuration reader and writer.
* Configuration File Manager.

## Requirements

* PHP 5.6+

## Usage

```php
use ialopezg\Libraries\Config\Config;

$config = Config::load('database.php');

// Get the value
echo $config->get('database.connections.default.db_driver');
// Change the value
$config->set('database.connections.default.db_driver', 'MSSQL');
// Print new value
echo $config->get('database.connections.default.db_driver');

// Write the file
$config->toFile('database.php');
```

Please, check `examples` directory for more details of usage.

## License
This project is under the MIT license. For more information see See [LICENSE](LICENSE).
