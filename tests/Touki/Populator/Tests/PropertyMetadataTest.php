<?php

namespace Touki\Populator\Tests;

use Touki\Populator\PropertyMetadata;

/**
 * Property Metadata Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class PropertyMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorGivesSetterOfItsName()
    {
        $metadata = new PropertyMetadata('foo');

        $this->assertEquals('setfoo', $metadata->getSetter());
    }

    public function testHasAliasWithoutAliasReturnsFalse()
    {
        $metadata = new PropertyMetadata('foo');

        $this->assertFalse($metadata->hasAlias('bar'));
    }

    public function testHasAliasWithAliasReturnsTrue()
    {
        $metadata = new PropertyMetadata('foo');
        $metadata->addAlias('bar');
        
        $this->assertTrue($metadata->hasAlias('bar'));
    }

    public function provideSetIgnoredParameters()
    {
        return array(
            array(true,  true),
            array(false, false),
            array(null,  false),
            array('foo', true),
            array(0,     false)
        );
    }

    /**
     * @dataProvider provideSetIgnoredParameters
     */
    public function testSetIgnoredGetsTransformedToBoolean($ignored, $expected)
    {
        $metadata = new PropertyMetadata('foo');
        $metadata->setIgnored($ignored);

        $this->assertSame($expected, $metadata->isIgnored());
    }
}
