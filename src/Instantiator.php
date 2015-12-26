<?php
/**
 * Instantiator.php
 *
 * @date 25.12.2015 13:50:09
 */

namespace Sufir\Hydrator;

use InvalidArgumentException;
use ReflectionClass;

/**
 * InstantiatorInterface
 *
 * Создание объектов (без использования конструктора).
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Hydrator
 */
final class Instantiator implements InstantiatorInterface
{
    /**
     * @var ReflectionClass[]
     */
    private static $reflectionsCache = array();
    /**
     * @var object[] Объекты, которые могут быть клонированы
     */
    private static $prototypesCache = array();

    /**
     * {@inheritdoc}
     */
    public function newInstance($className)
    {
        if (isset(self::$prototypesCache[$className])) {
            return clone self::$prototypesCache[$className];
        }

        $reflection = $this->getReflection($className);

        $object = $reflection->newInstanceWithoutConstructor();

        if ($this->isCloneable($reflection)) {
            self::$prototypesCache[$className] = clone $object;
        } else {
            self::$reflectionsCache[$className] = $reflection;
        }

        return $object;
    }

    /**
     *
     * @param string $className
     * @return ReflectionClass
     * @throws InvalidArgumentException
     */
    private function getReflection($className)
    {
        if (isset(self::$reflectionsCache[$className])) {
            return self::$reflectionsCache[$className];
        }

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class «{$className}» not found");
        }

        $reflection = new ReflectionClass($className);

        if ($reflection->isAbstract()) {
            throw new InvalidArgumentException("Cannot instantiate abstract class «{$className}»");
        }

        return $reflection;
    }

    /**
     *
     * @param ReflectionClass $reflection
     * @return boolean
     */
    private function isCloneable(ReflectionClass $reflection)
    {
        return (
            $reflection->isCloneable()
            && !$reflection->hasMethod('__clone')
        );
    }
}
