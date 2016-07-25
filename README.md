# A simple Db class - a high level wrapper on PDO

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/daydiff/simple_db/master.svg?style=flat-square)](https://travis-ci.org/daydiff/simple_db)

It's a very simple but handy and high level database wrapper on PDO. Usefull for small scripts and for systems without "normal" database abstraction.

## Install

Recommended way

``` bash
$ composer require daydiff/simple-db
```

Alternate way

``` bash
$ git clone https://github.com/daydiff/simple_db
```

## Usage

It's very simple like a class itself

``` php
use Daydiff\SimpleDb\Db;

$dsn = 'mysql:dbname=great_company;host=localhost';
$user = 'database_user';
$password = 'database_password';

$db = new Db($dsn, $user, $password);
$name = $db->scalar('SELECT name FROM user WHERE id = :id', [':id' => 1]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
