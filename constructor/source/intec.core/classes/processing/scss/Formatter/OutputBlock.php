<?php
namespace intec\core\processing\scss\Formatter;

/**
 * Output block
 */
class OutputBlock
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var array
     */
    public $selectors;

    /**
     * @var array
     */
    public $lines;

    /**
     * @var array
     */
    public $children;

    /**
     * @var \intec\core\processing\scss\Formatter\OutputBlock
     */
    public $parent;
}
