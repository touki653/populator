<?php

namespace Touki\Populator\Tests;

use Touki\Populator\Tests\Fixtures\OnePropertyClass;
use Touki\Populator\Tests\Fixtures\NoPropertiesClass;
use Touki\Populator\Tests\Fixtures\PublicPropertyClass;
use Touki\Populator\Hydrator;
use Touki\Populator\PropertyMetadata;

/**
 * Hydrator Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HydratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->hydrator = new Hydrator;
    }

    public function testHydrateEmptyDataReturnsSameObject()
    {
        $data    = array();
        $object  = new \stdClass;
        $context = $this->getMock('Touki\Populator\HydratorContextInterface');

        $this->assertEquals($object, $this->hydrator->hydrate($data, $object, $context));
    }

    public function testHydrateNoPropertyFoundReturnsGetsSkipped()
    {
        $data    = array('property' => 'foo');
        $object  = new \stdClass;
        $flat    = new \stdClass;
        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue(null))
        ;

        $this->assertEquals($flat, $this->hydrator->hydrate($data, $object, $context));
    }

    public function testHydrateIgnoredPropertyGetsSkipped()
    {
        $data   = array('property' => 'foo');
        $object = new \stdClass;
        $flat   = new \stdClass;

        $metadata = new PropertyMetadata('foo');
        $metadata->setIgnored(true);

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->assertEquals($flat, $this->hydrator->hydrate($data, $object, $context));
    }

    public function testHydrateExistantSetterHydratesObject()
    {
        $data   = array('property' => 'foo');
        $object = new OnePropertyClass;

        $metadata = new PropertyMetadata('property');
        $metadata->setSetter('setProperty');

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->assertNull($object->getProperty());

        $object = $this->hydrator->hydrate($data, $object, $context);

        $this->assertInstanceOf('Touki\Populator\Tests\Fixtures\OnePropertyClass', $object);
        $this->assertEquals('foo', $object->getProperty());
    }

    /**
     * @expectedException        Touki\Populator\Exception\HydratationException
     * @expectedExceptionMessage Undefined setter method Touki\Populator\Tests\Fixtures\NoPropertiesClass::setProperty for property 'property'
     */
    public function testHydrateNonExistantSetterAndNoPublicVarThrowsException()
    {
        $data   = array('property' => 'foo');
        $object = new NoPropertiesClass;

        $metadata = new PropertyMetadata('foo');
        $metadata->setSetter('setProperty');

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->hydrator->hydrate($data, $object, $context);
    }

    public function testHydrateNonExistantSetterAccessPublicVar()
    {
        $data   = array('property' => 'foo');
        $object = new PublicPropertyClass;

        $metadata = new PropertyMetadata('property');
        $metadata->setSetter('setProperty'); // Unknown

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->assertNull($object->property);

        $object = $this->hydrator->hydrate($data, $object, $context);

        $this->assertInstanceOf('Touki\Populator\Tests\Fixtures\PublicPropertyClass', $object);
        $this->assertEquals('foo', $object->property);
    }

    public function testHydrateDeepContextAndNonArrayGetsSkipped()
    {
        $data   = array('property' => 'foo');
        $object = new OnePropertyClass;
        $flat   = new OnePropertyClass;

        $subContext = $this->getMock('Touki\Populator\HydratorContextInterface');

        $metadata = new PropertyMetadata('property');
        $metadata->setSetter('setProperty');
        $metadata->setContext($subContext);

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->assertEquals($flat, $this->hydrator->hydrate($data, $object, $context));
    }

    public function testHydrateDeepContextInstanciatesAndHydratesSubProperty()
    {
        $data   = array('property' => array ('property' => 'foo'));
        $object = new OnePropertyClass;

        $subMetadata = new PropertyMetadata('property');
        $subMetadata->setSetter('setProperty');

        $subContext = $this->getMock('Touki\Populator\HydratorContextInterface');
        $subContext
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($subMetadata))
        ;

        $subContext
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('Touki\Populator\Tests\Fixtures\OnePropertyClass'))
        ;

        $metadata = new PropertyMetadata('property');
        $metadata->setSetter('setProperty');
        $metadata->setContext($subContext);

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->assertNull($object->getProperty());

        $object = $this->hydrator->hydrate($data, $object, $context);

        $this->assertInstanceOf('Touki\Populator\Tests\Fixtures\OnePropertyClass', $object);
        $this->assertInstanceOf('Touki\Populator\Tests\Fixtures\OnePropertyClass', $object->getProperty());
        $this->assertEquals('foo', $object->getProperty()->getProperty());
    }

    /**
     * @expectedException        Touki\Populator\Exception\HydratationException
     * @expectedExceptionMessage Class Unknown\FooClass does not exist for property 'property'
     */
    public function testHydrateDeepContextOnNonExistantSubClassThrowsException()
    {
        $data   = array('property' => array ('property' => 'foo'));
        $object = new OnePropertyClass;

        $subMetadata = new PropertyMetadata('property');
        $subMetadata->setSetter('setProperty');

        $subContext = $this->getMock('Touki\Populator\HydratorContextInterface');
        $subContext
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('Unknown\FooClass'))
        ;

        $metadata = new PropertyMetadata('property');
        $metadata->setSetter('setProperty');
        $metadata->setContext($subContext);

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');
        $context
            ->expects($this->once())
            ->method('getProperty')
            ->will($this->returnValue($metadata))
        ;

        $this->hydrator->hydrate($data, $object, $context);
    }
}
