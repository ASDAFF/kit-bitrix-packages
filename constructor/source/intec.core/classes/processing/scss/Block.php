<?php
namespace intec\core\processing\scss;

/**
 * Block
 */
class Block
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var \intec\core\processing\scss\Block
     */
    public $parent;

    /**
     * @var integer
     */
    public $sourceIndex;

    /**
     * @var integer
     */
    public $sourceLine;

    /**
     * @var integer
     */
    public $sourceColumn;

    /**
     * @var array
     */
    public $selectors;

    /**
     * @var array
     */
    public $comments;

    /**
     * @var array
     */
    public $children;
}
