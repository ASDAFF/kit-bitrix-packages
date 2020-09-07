<?php
namespace intec\core\processing\scss;

use intec\core\processing\scss\Base\Range;
use intec\core\processing\scss\Exception\RangeException;

/**
 * Utilties
 */
class Util
{
    /**
     * Asserts that `value` falls within `range` (inclusive), leaving
     * room for slight floating-point errors.
     * @param string $name  The name of the value. Used in the error message.
     * @param Range  $range Range of values.
     * @param array  $value The value to check.
     * @param string $unit  The unit of the value. Used in error reporting.
     * @return mixed `value` adjusted to fall within range, if it was outside by a floating-point margin.
     * @throws \intec\core\processing\scss\Exception\RangeException
     */
    public static function checkRange($name, Range $range, $value, $unit = '')
    {
        $val = $value[1];
        $grace = new Range(-0.00001, 0.00001);

        if ($range->includes($val)) {
            return $val;
        }

        if ($grace->includes($val - $range->first)) {
            return $range->first;
        }

        if ($grace->includes($val - $range->last)) {
            return $range->last;
        }

        throw new RangeException("$name {$val} must be between {$range->first} and {$range->last}$unit");
    }
}
