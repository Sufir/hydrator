<?php
/**
 * PlainArrayHydrator.php
 *
 * @date 25.12.2015 15:47:21
 */

namespace Sufir\Hydrator;

use Closure;
use ReflectionObject;
use ReflectionClass;
use Sufir\Hydrator\Instantiator;
use InvalidArgumentException;

/**
 * PlainArrayHydrator
 *
 * Обеспечивает наполнение и извлечение данных из объекта.
 *
 * @todo refactoring
 * @todo hedrate and extraction internal classes
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Hydrator
 */
class PlainArrayHydrator implements HydratorInterface
{
    /**
     * {@inhereitdoc}
     */
    public function hydrate(array $data, $object)
    {
        if (is_string($object)) {
            $object = (new Instantiator())->newInstance($object);
        }

        $className = get_class($object);
        $selfProperties = [];
        $subObjects = [];

        foreach ($data as $property => $value) {
            if (substr($property, 0, 1) !== '_') {
                $selfProperties[$property] = $value;
            } elseif (substr($property, 0, 2) === '__') {
                $sub = explode('_', trim($property, '_'));
                if (count($sub) === 2) {
                    $subObjects[$sub[0]][$sub[1]] = $value;
                }
            }
        }

        foreach ($subObjects as $class => $properties) {
            $class = $this->parsePropAndClass($class, $className);
            $selfProperties[$class['property']] = $this->hydrate($properties, $class['class']);
        }

        $hydrate = $this->getHydrator($className);
        $hydrate($object, $selfProperties);

        return $object;
    }

    /**
     * {@inhereitdoc}
     */
    public function extract($object)
    {
        $data = [];
        $reflection = new ReflectionObject($object);
        $class = $reflection->getName();
        $propertises = $reflection->getProperties();

        foreach ($propertises as $prop) {
            $prop->setAccessible(true);
            $value = $prop->getValue($object);
            $propName = $prop->getName();

            if (!is_object($value)) {
                $data[$propName] = $value;
            } else {
                $fullClassName = get_class($value);
                if (strpos($fullClassName, $class) === 0) {
                    $className = substr($fullClassName, strrpos($fullClassName, '\\')+1);
                } else {
                    $className = '\\' . $fullClassName;
                }

                $data['__' . lcfirst($propName) . ':' . $className] = $this->extract($value);
            }
        }

        foreach ($data as $key => $value) {
            if (substr($key, 0, 2) === '__' && is_array($value)) {
                foreach ($value as $subKey => $subVal) {
                    $data["{$key}_{$subKey}"] = $subVal;
                }
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function parsePropAndClass($className, $namespace = null)
    {
        $parts = explode(":", $className, 2);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException(
                "Wrong property and type definition {$className}, must in format «__propertyName:SubClassName» or «__propertyName:\ClassName»"
            );
        }

        $prop = $parts[0];
        $class = $parts[1];

        if (strpos($class, '\\') === 0) {
            $namespace = '';
        } else {
            $namespace = trim($namespace, " \t\n\r\0\x0B\\\/") . '\\';
        }

        return [
            'property' => $prop,
            'class' => $namespace . $class,
        ];
    }

    protected function getHydrator($className)
    {
        $reflection = new ReflectionClass($className);

        if (!$reflection->isUserDefined()) {
            throw new InvalidArgumentException(
                "Cannot hydrate internal class «{$className}»"
            );
        }

        return Closure::bind(function ($object, $data) {
            foreach ($data as $property => $value) {
                if (property_exists($object, $property)) {
                    $object->{$property} = $value;
                }
            }
        }, null, $className);
    }
}
