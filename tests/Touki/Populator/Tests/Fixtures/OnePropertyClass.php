<?php

namespace Touki\Populator\Tests\Fixtures;

class OnePropertyClass
{
    protected $property;

    /**
     * Get Property
     *
     * @return string Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set Property
     *
     * @param string $property Property
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }
}
