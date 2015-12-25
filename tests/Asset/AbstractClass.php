<?php
/**
 * AbstractClass.php
 *
 * @date 25.12.2015 16:46:14
 */

namespace Sufir\Hydrator\Test\Asset;

use BadMethodCallException;

abstract class AbstractClass
{
    public function __construct()
    {
        throw new BadMethodCallException;
    }
}
