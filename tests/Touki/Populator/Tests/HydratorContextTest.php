<?php

namespace Touki\Populator\Tests;

use Touki\Populator\PropertyMetadata;
use Touki\Populator\HydratorContext;

/**
 * Hydrator Context Test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HydratorContextTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = new HydratorContext;
    }

    public function testGetPropertyReturnsNullWhenNoPropertyIsPresent()
    {
        $this->assertNull($this->context->getProperty('foo'));
    }

    public function testGetPropertyReturnsNullWhenNoPropertyIsMatched()
    {
        $this->context->addProperty(new PropertyMetadata('bar'));

        $this->assertNull($this->context->getProperty('foo'));
    }

    public function testGetPropertyGetFoundOnName()
    {
        $prop = new PropertyMetadata('foo');
        $this->context->addProperty($prop);
        
        $this->assertSame($prop, $this->context->getProperty('foo'));
    }

    public function testGetPropertyGetFoundOnAlias()
    {
        $prop = new PropertyMetadata('foo');
        $prop->addAlias('bar');
        $this->context->addProperty($prop);
        
        $this->assertSame($prop, $this->context->getProperty('bar'));
    }
}

