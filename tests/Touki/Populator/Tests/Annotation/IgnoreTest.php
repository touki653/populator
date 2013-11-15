<?php

namespace Touki\Populator\Tests\Annotation;

use Touki\Populator\Annotation\Ignore;
use Touki\Populator\PropertyMetadata;

/**
 * Ignore annotation test Case
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class IgnoreTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessSetsMetadataIgnoreToTrue()
    {
        $ignore   = new Ignore;
        $metadata = new PropertyMetadata('test');
        $factory  = $this->getMock('Touki\Populator\HydratorContextFactoryInterface');

        $this->assertFalse($metadata->isIgnored());

        $ignore->process($metadata, $factory);

        $this->assertTrue($metadata->isIgnored());
    }
}
