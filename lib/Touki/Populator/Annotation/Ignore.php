<?php

namespace Touki\Populator\Annotation;

use Touki\Populator\PopulatorAnnotation;
use Touki\Populator\PropertyMetadata;
use Touki\Populator\HydratorContextFactoryInterface;

/**
 * Annotation class to ignore setting of the property
 * 
 * @Annotation
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Ignore implements PopulatorAnnotation
{
    /**
     * {@inheritDoc}
     */
    public function process(PropertyMetadata $metadata, HydratorContextFactoryInterface $factory)
    {
        $metadata->setIgnored(true);
    }
}
