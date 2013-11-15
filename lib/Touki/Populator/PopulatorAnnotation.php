<?php

namespace Touki\Populator;

/**
 * Base Interface for any Populator Annotation
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface PopulatorAnnotation
{
    /**
     * Proccesses the metadata
     *
     * @param  PropertyMetadata                $metadata Metadata
     * @param  HydratorContextFactoryInterface $factory  Factory fallback if needed
     */
    public function process(PropertyMetadata $metadata, HydratorContextFactoryInterface $factory);
}
