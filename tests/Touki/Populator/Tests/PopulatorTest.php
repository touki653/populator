<?php

namespace Touki\Populator\Tests;

use Touki\Populator\Populator;

/**
 * Populator test case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PopulatorTest extends \PHPUnit_Framework_TestCase
{
    protected $populator;

    public function setUp()
    {
        
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class Unknown\FooClass does not exist
     */
    public function testPopulateOnNonExistantClassNameThrowsException()
    {
        $hydrator = $this->getMock('Touki\Populator\HydratorInterface');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $populator = new Populator($hydrator, $factory);
        $populator->populate(array(), 'Unknown\FooClass');
    }

    public function testPopulateOnObjectGivenReturnsSameObject()
    {
        $object = new \stdClass;
        $data = array();

        $hydrator = $this->getMock('Touki\Populator\HydratorInterface');
        $hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo($data), $this->equalTo($object))
            ->will($this->returnValue($object))
        ;

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');

        $factory = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');
        $factory
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($context))
        ;

        $populator = new Populator($hydrator, $factory);

        $this->assertEquals($object, $populator->populate($data, $object));
    }

    public function testPopulateOnClassnameInstanciatesAndReturnsIt()
    {
        $object = 'stdClass';
        $data = array();

        $hydrator = $this->getMock('Touki\Populator\HydratorInterface');
        $hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo($data), $this->equalTo(new \stdClass))
            ->will($this->returnValue(new \stdClass))
        ;

        $context = $this->getMock('Touki\Populator\HydratorContextInterface');

        $factory = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');
        $factory
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($context))
        ;

        $populator = new Populator($hydrator, $factory);

        $this->assertSame($object, get_class($populator->populate($data, $object)));
    }
}
