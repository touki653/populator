<?php

namespace Touki\Populator;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Main Populator class
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Populator
{
    /**
     * Context Factory
     * @var HydratorContextFactoryInterface
     */
    protected $factory;

    /**
     * Hydrator
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * Constructor
     *
     * @param HydratorInterface               $hydrator An Hydrator
     * @param HydratorContextFactoryInterface $factory  An Hydrator Context Factory
     */
    public function __construct(HydratorInterface $hydrator = null, HydratorContextFactoryInterface $factory = null)
    {
        $this->hydrator = $hydrator ?: new Hydrator;
        $this->factory  = $factory ?: new HydratorContextFactory(new AnnotationReader);
    }

    /**
     * Populates the given data to the given object
     *
     * @param array $data   Data to fetch
     * @param mixed $object Object or class name to hydrate
     *
     * @return object Hydrated object
     */
    public function populate(array $data, $object)
    {
        if (!is_object($object)) {
            if (!class_exists($object)) {
                throw new \InvalidArgumentException(sprintf("Class %s does not exist", $object));
            }

            $object = new $object;
        }

        $context = $this->factory->build($object);

        return $this->hydrator->hydrate($data, $object, $context);
    }
}
