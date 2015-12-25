# sufir/hydrator

## Install

```bash
$ composer require sufir/hydrator
```

## Example

Using Instantiator.

```php
<?php
use Sufir\Hydrator\Instantiator;

$instantiator = new Instantiator();
$newObject = $instantiator->newInstance('\\Some\\ClassName');
```

Using Hydrators.

```php
<?php
// @todo
```

## Authors

- [sufir](http://git.ls1.ru/u/sufir)