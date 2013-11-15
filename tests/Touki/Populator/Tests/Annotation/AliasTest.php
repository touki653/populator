<?php

namespace Touki\Populator\Tests\Annotation;

use Touki\Populator\Annotation\Alias;
use Touki\Populator\PropertyMetadata;

/**
 * Alias annotation test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class AliasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage No value specified for Touki\Populator\Annotation\Alias
     */
    public function testConstructOnNoValueSetThrowsException()
    {
        new Alias(array());
    }

    public function testProcessAddsAnAlias()
    {
        $alias    = new Alias(array('value' => 'foo'));
        $metadata = new PropertyMetadata('test');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $this->assertCount(0, $metadata->getAliases());

        $alias->process($metadata, $factory);

        $this->assertCount(1, $metadata->getAliases());
        $this->assertSame(array('foo'), $metadata->getAliases());

        $alias->process($metadata, $factory);

        $this->assertCount(2, $metadata->getAliases());
        $this->assertSame(array('foo', 'foo'), $metadata->getAliases());
    }
}
