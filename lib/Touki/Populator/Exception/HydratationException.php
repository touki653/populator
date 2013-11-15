<?php

namespace Touki\Populator\Exception;

use RuntimeException;

/**
 * Exception thrown on an error while hydrating
 *
 * @author Touki <g.vincendon@vithemis.com>
 */
class HydratationException extends RuntimeException implements PopulatorException
{
}
