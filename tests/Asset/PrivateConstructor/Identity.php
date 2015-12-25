<?php
/**
 * Identity.php
 *
 * @date 25.12.2015 17:59:16
 */

namespace Sufir\Hydrator\Test\Asset\PrivateConstructor;

class Identity
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    function getValue()
    {
        return $this->id;
    }
}
