<?php

namespace Touki\Populator\Tests\Annotation;

use Touki\Populator\Annotation\Setter;
use Touki\Populator\PropertyMetadata;

/**
 * Setter annotation test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class SetterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No value specified for Touki\Populator\Annotation\Setter
     */
    public function testConstructOnNoValueSetThrowsException()
    {
        new Setter(array());
    }

    public function testProcessSetsMetadataSetter()
    {
        $setter   = new Setter(array('value' => 'setFoo'));
        $metadata = new PropertyMetadata('test');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $this->assertEquals('settest', $metadata->getSetter());

        $setter->process($metadata, $factory);

        $this->assertEquals('setFoo', $metadata->getSetter());
    }
}
