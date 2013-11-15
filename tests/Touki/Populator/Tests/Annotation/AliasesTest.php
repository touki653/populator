<?php

namespace Touki\Populator\Tests\Annotation;

use Touki\Populator\Annotation\Aliases;
use Touki\Populator\PropertyMetadata;

/**
 * Aliases annotation test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class AliasesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No value specified for Touki\Populator\Annotation\Aliases
     */
    public function testConstructOnNoValueSetThrowsException()
    {
        new Aliases(array());
    }

    public function testProcessSetsAndReplaceAliasesOnSingleValue()
    {
        $aliases  = new Aliases(array('value' => 'foo'));
        $metadata = new PropertyMetadata('test');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $this->assertCount(0, $metadata->getAliases());

        $aliases->process($metadata, $factory);

        $this->assertCount(1, $metadata->getAliases());
        $this->assertSame(array('foo'), $metadata->getAliases());

        $aliases->process($metadata, $factory);

        $this->assertCount(1, $metadata->getAliases());
        $this->assertSame(array('foo'), $metadata->getAliases());
    }

    public function testProcessSetsAndReplaceAliasesOnArrayValue()
    {
        $aliases  = new Aliases(array(
            'value' => array('foo', 'bar')
        ));
        $metadata = new PropertyMetadata('test');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $this->assertCount(0, $metadata->getAliases());

        $aliases->process($metadata, $factory);

        $this->assertCount(2, $metadata->getAliases());
        $this->assertSame(array('foo', 'bar'), $metadata->getAliases());

        $aliases->process($metadata, $factory);

        $this->assertCount(2, $metadata->getAliases());
        $this->assertSame(array('foo', 'bar'), $metadata->getAliases());
    }
}
