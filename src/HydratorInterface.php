<?php
/**
 * HydratorInterface.php
 *
 * @date 25.12.2015 13:37:51
 * @copyright Sklyarov Alexey <sufir@mihailovka.info>
 */

namespace Sufir\Hydrator;

/**
 * HydratorInterface
 *
 * Description of HydratorInterface
 *
 * @author Sklyarov Alexey <sufir@mihailovka.info>
 * @package Sufir\Hydrator
 */
interface HydratorInterface
{
    /**
     * Наполняет объект $object данными представленными в $data.
     * <br>
     * Если в $object вместо объекта передано имя класса, объект будет создан.
     *
     * @param array $data
     * @param object|string $object
     * @return object
     */
    public function hydrate(array $data, $object);

    /**
     * Извлекает значения из объекта.
     *
     * @param object $object
     * @return array
     */
    public function extract($object);
}
