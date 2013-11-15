<?php

namespace Touki\Populator;

use Doctrine\Common\Annotations\Reader;

/**
 * Factory class which creates HydratorContext objects
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HydratorContextFactory implements HydratorContextFactoryInterface
{
    /**
     * Annotation Reader
     * @var Reader
     */
    protected $reader;

    /**
     * Constructor
     *
     * @param Reader $reader Annotation reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function build($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf("Class %s does not exist", $class));
        }

        $context = new HydratorContext;
        $context->setClass($class);

        $reflection = new \ReflectionClass($class);

        foreach ($reflection->getProperties() as $property) {
            $annotations = $this->reader->getPropertyAnnotations($property);

            $metadata = new PropertyMetadata($property->getName());

            foreach ($annotations as $annotation) {
                if ($annotation instanceof PopulatorAnnotation) {
                    $annotation->process($metadata, $this);
                }
            }

            $context->addProperty($metadata);
        }

        return $context;
    }
}
