<?php
/**
 * PlainPropertysHydratorTest.php
 *
 * @date 25.12.2015 17:48:09
 */

namespace Sufir\Hydrator\Test;

use Sufir\Hydrator\PlainArrayHydrator as Hydrator;
use Sufir\Hydrator\Test\Asset\PrivateConstructor\Identity;
use Sufir\Hydrator\Test\Asset\UncloneableClass;
use stdClass;
use Sufir\Hydrator\Test\Asset\PrivateConstructor;

class PlainArrayHydrator extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var string
     */
    private $hydrateClass = '\Sufir\Hydrator\Test\Asset\PrivateConstructor';

    /**
     * @var array
     */
    private $hydrateData = [
        'firstName' => 'John',
        'lastName' => 'Doe',
        '__identity:Identity_id' => 100500,
        '__stdClass:\stdClass_unknownProperty' => '!undefined',
        '__uncloneableClass:\Sufir\Hydrator\Test\Asset\UncloneableClass_someProperty1' => 'value 1',
        '__uncloneableClass:\Sufir\Hydrator\Test\Asset\UncloneableClass_someProperty2' => null,
        '__uncloneableClass:\Sufir\Hydrator\Test\Asset\UncloneableClass_unknownProperty' => '!undefined',
    ];

    protected function setUp()
    {
        $this->hydrator = new Hydrator();
    }

    /**
     * @return PrivateConstructor
     */
    public function testHydratye()
    {
        $data = $this->hydrateData;

        /* @var $object PrivateConstructor */
        $object = $this->hydrator->hydrate($data, $this->hydrateClass);

        $this->assertInstanceOf($this->hydrateClass, $object);

        $this->assertInstanceOf(Identity::class, $object->getIdentity());
        $this->assertInstanceOf(UncloneableClass::class, $object->getUncloneableClass());
        $this->assertInstanceOf(stdClass::class, $object->getStdClass());

        $this->assertEquals($data['__identity:Identity_id'], $object->getIdentity()->getValue());
        $this->assertEquals($data['firstName'], $object->getFirstName());
        $this->assertEquals($data['lastName'], $object->getLastName());
        $this->assertAttributeEquals(
            $data['__uncloneableClass:\Sufir\Hydrator\Test\Asset\UncloneableClass_someProperty1'],
            'someProperty1',
            $object->getUncloneableClass()
        );
        $this->assertAttributeEquals(
            $data['__uncloneableClass:\Sufir\Hydrator\Test\Asset\UncloneableClass_someProperty2'],
            'someProperty2',
            $object->getUncloneableClass()
        );

        $this->assertFalse(property_exists($object->getStdClass(), 'unknownProperty'));
        $this->assertFalse(property_exists($object->getUncloneableClass(), 'unknownProperty'));

        return $object;
    }

    /**
     * @depends testHydratye
     */
    public function testExtract($object)
    {
        $data = $this->hydrator->extract($object);

        $expected = [];
        foreach ($this->hydrateData as $prop => $value) {
            if ($value !== '!undefined') {
                $expected[$prop] = $value;
            }
        }

        $this->assertEquals($expected, $data);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongFormat()
    {
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            '__identityIdentity_id' => 100500,
        ];

        $this->hydrator->hydrate($data, $this->hydrateClass);
    }
}
