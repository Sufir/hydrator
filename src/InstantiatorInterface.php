<?php
/**
 * InstantiatorInterface.php
 *
 * @date 25.12.2015 13:44:29
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Hydrator;

use InvalidArgumentException;
/**
 * InstantiatorInterface
 *
 * Создание объектов (без использования конструктора).
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Hydrator
 */
interface InstantiatorInterface
{
    /**
     * Создает новый объект указанного класса.
     *
     * @param string $className
     * @return object
     * @throws InvalidArgumentException
     */
    public function newInstance($className);
}
