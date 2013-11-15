<?php

namespace Touki\Populator\Tests\Annotation;

use Touki\Populator\Annotation\Deep;
use Touki\Populator\PropertyMetadata;

/**
 * Deep annotation test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class DeepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No value specified for Touki\Populator\Annotation\Deep
     */
    public function testConstructOnNoValueSetThrowsException()
    {
        new Deep(array());
    }

    public function testProcessSetsAContext()
    {
        $deep     = new Deep(array('value' => 'Foo'));
        $metadata = new PropertyMetadata('test');
        $context  = $this->getMock('Touki\Populator\HydratorContextInterface');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');
        $factory
            ->expects($this->once())
            ->method('build')
            ->will($this->returnValue($context))
        ;

        $this->assertNull($metadata->getContext());

        $deep->process($metadata, $factory);

        $this->assertSame($context, $metadata->getContext());
    }
}
