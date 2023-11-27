# php
School projects made using the php programming language.

## Getting Started

Install libraries

```
composer require League/Plates
```

Update 
```
composer update
```

## Database 

File structure

```bash
pdo
  ├── composer.json
  ├── composer.lock
  ├── conf
  │   └── config.php
  ├── index.php
  ├── Model
  │   └── StudenteRepository.php
  ├── templates
  │   └── index.tpl
  └── Util
      └── Connection.php
```

Creating the Data Source Name

```php
$dsn = 'mysql:host={host};dbname={dbname};charset=utf8';
```

Connecting to the Database

```php
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
```

