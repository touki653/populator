<?php

namespace Touki\Populator;

/**
 * Base interface which any HydratorContextFactory must implement
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface HydratorContextFactoryInterface
{
    /**
     * Builds an Hydrator Context
     *
     * @param mixed $object An arbitrary object or its classname
     *
     * @return HydratorContextInterface A Context
     */
    public function build($object);
}
