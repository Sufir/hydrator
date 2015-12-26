# sufir/hydrator

## Install

Via Composer

```bash
$ composer require sufir/hydrator
```

## Usage

Using Instantiator.

```php
<?php
use Sufir\Hydrator\Instantiator;

$instantiator = new Instantiator();
$newObject = $instantiator->newInstance('\Some\ClassName');
```

Using Hydrators.

```php
<?php
use Sufir\Hydrator\PlainArrayHydrator as Hydrator;

$className = '\Namespace\Domain\Entity';
$hydrateData = [
    // simple properties
    'firstName' => 'John',
    'lastName' => 'Doe',
    // id property of sub class \Namespace\Domain\Entity\Identity
    '__identity:Identity_id' => 100500,
    // some properties in subclass from another namespace
    '__uncloneableClass:\vendor\Some\Super\Class_someProperty1' => 'value 1',
    '__uncloneableClass:\vendor\Some\Super\Class_someProperty2' => null,
    // unexpected properties of subclass will be ignored
    '__uncloneableClass:\vendor\Some\Super\Class_unknownProperty' => '!undefined',
    '__stdClass:\stdClass_unknownProperty' => '!undefined',
];

/* @var $object PrivateConstructor */
$object = $this->hydrator->hydrate($data, $className);

var_dump($object);
```

```
class \Namespace\Domain\Entity (5) {
    protected $firstName => string(4) "John"
    protected $lastName => string(3) "Doe"
    protected $identity =>
        class \Namespace\Domain\Entity\Identity {
            protected $id => int(100500)
        }
    protected $uncloneableClass =>
        class \vendor\Some\Super\Class (2) {
            public $someProperty1 => string(7) "value 1"
            public $someProperty2 => NULL
        }
    protected $stdClass =>
        class stdClass (0) {
        }
}
```

## Testing

``` bash
$ composer test
```

## Credits

- [sufir](http://git.ls1.ru/u/sufir)

## License

The MIT License (MIT).