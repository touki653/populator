<?php

namespace Touki\Populator\Tests;

use Touki\Populator\HydratorContextFactory;
use Touki\Populator\Tests\Fixtures\NoPropertiesClass;
use Touki\Populator\Tests\Fixtures\OnePropertyClass;

/**
 * Hdyrator Context Factory Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HydratorContextFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $annotation = $this->getMock('Touki\Populator\PopulatorAnnotation');
        $annotations = array($annotation);

        $reader = $this->getMock('Doctrine\Common\Annotations\Reader');
        $reader
            ->expects($this->any())
            ->method('getPropertyAnnotations')
            ->will($this->returnValue($annotations))
        ;

        $this->factory = new HydratorContextFactory($reader);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class Foobaz does not exist
     */
    public function testBuildNonExistingClassnameThrowsInvalidArgumentException()
    {
        $this->factory->build('Foobaz');
    }

    public function provideNoPropertiesClassNameAndObject()
    {
        return array(
            array(new NoPropertiesClass),
            array(get_class(new NoPropertiesClass))
        );
    }

    /**
     * @dataProvider provideNoPropertiesClassNameAndObject
     */
    public function testBuildNoPropertiesClassReturnsEmptyContext($class)
    {
        $context = $this->factory->build($class);

        $this->assertInstanceOf('Touki\Populator\HydratorContextInterface', $context);
        $this->assertEquals('Touki\Populator\Tests\Fixtures\NoPropertiesClass', $context->getClass());
        $this->assertEmpty($context->getProperties());
    }

    public function provideClassNameAndObject()
    {
        return array(
            array(new OnePropertyClass),
            array(get_class(new OnePropertyClass))
        );
    }

    /**
     * @dataProvider provideClassNameAndObject
     */
    public function testBuildOnePropertyClassReturnsMockedAnnotation($class)
    {
        $annotation = $this->getMock('Touki\Populator\PopulatorAnnotation');
        $annotation
            ->expects($this->once())
            ->method('process')
            ->will($this->returnCallback(function ($metadata, $factory) {
                $metadata->setIgnored(true);
            }))
        ;
        $annotations = array($annotation);

        $reader = $this->getMock('Doctrine\Common\Annotations\Reader');
        $reader
            ->expects($this->any())
            ->method('getPropertyAnnotations')
            ->will($this->returnValue($annotations))
        ;

        $this->factory = new HydratorContextFactory($reader);

        $context = $this->factory->build($class);

        $this->assertInstanceOf('Touki\Populator\HydratorContextInterface', $context);
        $this->assertEquals('Touki\Populator\Tests\Fixtures\OnePropertyClass', $context->getClass());

        $props = $context->getProperties();
        $this->assertCount(1, $props);

        $prop = $props[0];
        $this->assertInstanceOf('Touki\Populator\PropertyMetadata', $prop);
        $this->assertEquals('property', $prop->getName());
        $this->assertTrue($prop->isIgnored());
    }
}
