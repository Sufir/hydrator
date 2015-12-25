<?php
/**
 * UncloneableClass.php
 *
 * @date 25.12.2015 16:50:46
 */

namespace Sufir\Hydrator\Test\Asset;

use BadMethodCallException;

class UncloneableClass
{
    public $someProperty1,
        $someProperty2;

    public function __construct()
    {
        throw new BadMethodCallException;
    }

    public function __clone()
    {
        throw new BadMethodCallException;
    }
}
