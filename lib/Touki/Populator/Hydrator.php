<?php

namespace Touki\Populator;

use Touki\Populator\Exception\HydratationException;

/**
 * Main hydratation class which processes the hydratations
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class Hydrator implements HydratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data = array(), $object, HydratorContextInterface $context)
    {
        foreach ($data as $key => $value) {
            if (null === $property = $context->getProperty($key)) {
                continue;
            }

            if ($property->isIgnored()) {
                continue;
            }

            $setter = $property->getSetter();
            $caller = array($object, $setter);

            if (!method_exists($object, $setter)) {
                if (!array_key_exists($key, get_object_vars($object))) {
                    throw new HydratationException(sprintf(
                        "Undefined setter method %s::%s for property '%s'",
                        get_class($object),
                        $setter,
                        $key
                    ));
                }

                $caller = function($value) use ($object, $key) {
                    $object->$key = $value;
                };
            }

            if (null !== $subContext = $property->getContext()) {
                if (!is_array($value)) {
                    continue;
                }

                $subClass = $subContext->getClass();

                if (!class_exists($subClass)) {
                    throw new HydratationException(sprintf(
                        "Class %s does not exist for property '%s'",
                        $subClass,
                        $key
                    ));
                }

                $subObject = new $subClass;
                $value = $this->hydrate($value, $subObject, $subContext);
            }

            call_user_func_array($caller, array($value));
        }

        return $object;
    }
}
