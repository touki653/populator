<?php

namespace Touki\Populator;

/**
 * Base interface for an Hydrator Context
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
interface HydratorContextInterface
{
    /**
     * Get class namespace
     *
     * @return string Class namespace
     */
    public function getClass();

    /**
     * Finds a property by name or alias
     *
     * @param string $name Property name or alias
     *
     * @return PropertyMetadata
     */
    public function getProperty($name);
}
