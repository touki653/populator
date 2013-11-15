<?php

namespace Touki\Populator;

/**
 * Base interface which any hydrator must implement
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface HydratorInterface
{
    /**
     * Processes the hydratation of the $object, from $data
     *
     * @param array                    $data    Input data
     * @param object                   $object  Object
     * @param HydratorContextInterface $context Hydrator Context
     *
     * @return object Hydrated object
     */
    public function hydrate(array $data = array(), $object, HydratorContextInterface $context);
}
