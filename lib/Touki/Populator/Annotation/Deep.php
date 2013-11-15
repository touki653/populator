<?php

namespace Touki\Populator\Annotation;

use Touki\Populator\PopulatorAnnotation;
use Touki\Populator\PropertyMetadata;
use Touki\Populator\HydratorContextFactoryInterface;

/**
 * Deep Annotation
 * 
 * @Annotation
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Deep implements PopulatorAnnotation
{
    protected $value;

    /**
     * Constructor
     *
     * @param array $data Annotation Data
     */
    public function __construct(array $data)
    {
        if (!isset($data['value'])) {
            throw new \InvalidArgumentException(sprintf("No value specified for %s", get_class($this)));
        }

        $this->value = $data['value'];
    }

    /**
     * {@inheritDoc}
     */
    public function process(PropertyMetadata $metadata, HydratorContextFactoryInterface $factory)
    {
        $metadata->setContext($factory->build($this->value));
    }
}
