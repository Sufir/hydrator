<?php
/**
 * PrivateConstructor.php
 *
 * @date 25.12.2015 16:47:38
 */

namespace Sufir\Hydrator\Test\Asset;

use BadMethodCallException;
use Sufir\Hydrator\Test\Asset\PrivateConstructor\Identity;
use Sufir\Hydrator\Test\Asset\UncloneableClass;
use stdClass;

class PrivateConstructor
{
    private function __construct()
    {
        throw new BadMethodCallException;
    }

    /**
     * @var Identity
     */
    protected $identity;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var UncloneableClass
     */
    protected $uncloneableClass;

    /**
     * @var stdClass
     */
    protected $stdClass;

    /**
     * @return Identity
     */
    function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return UncloneableClass
     */
    function getUncloneableClass()
    {
        return $this->uncloneableClass;
    }

    /**
     * @return stdClass
     */
    function getStdClass()
    {
        return $this->stdClass;
    }
}
