<?php
/**
 * InstantiatorTest.php
 *
 * @date 25.12.2015 16:29:00
 */

namespace Sufir\Hydrator\Test;

use Sufir\Hydrator\Instantiator;

class InstantiatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Instantiator
     */
    private $instantiator;

    protected function setUp()
    {
        $this->instantiator = new Instantiator();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateAbstract()
    {
        $this->instantiator->newInstance('\Sufir\Hydrator\Test\Asset\AbstractClass');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateTrait()
    {
        $this->instantiator->newInstance('\Sufir\Hydrator\Test\Asset\TraitAsset');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateTraitNotExisting()
    {
        $this->instantiator->newInstance('\Some\Not\Existing\Class');
    }

    /**
     * @param string $className
     * @dataProvider getClassList
     */
    public function testCanInstantiate($className)
    {
        $this->assertInstanceOf($className, $this->instantiator->newInstance($className));
    }

    /**
     * @param string $className
     * @dataProvider getClassList
     */
    public function testInstantiatesSeparateInstances($className)
    {
        $instance1 = $this->instantiator->newInstance($className);
        $instance2 = $this->instantiator->newInstance($className);
        $this->assertEquals($instance1, $instance2);
        $this->assertNotSame($instance1, $instance2);
    }

    /**
     * @return string[][]
     */
    public function getClassList()
    {
        return [
            ['\stdClass'],
            [__CLASS__],
            ['\Sufir\Hydrator\Test\Asset\PrivateConstructor'],
            ['\Sufir\Hydrator\Test\Asset\UncloneableClass'],
        ];
    }
}
