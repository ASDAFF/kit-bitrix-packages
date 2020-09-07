<?php
namespace intec\core\processing\scss;

/**
 * Base node
 */
abstract class Node
{
    /**
     * @var string
     */
    public $type;

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
}
