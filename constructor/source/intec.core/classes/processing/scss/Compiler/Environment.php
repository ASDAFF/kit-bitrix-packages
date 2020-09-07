<?php
namespace intec\core\processing\scss\Compiler;

/**
 * Compiler environment
 */
class Environment
{
    /**
     * @var \intec\core\processing\scss\Block
     */
    public $block;

    /**
     * @var \intec\core\processing\scss\Compiler\Environment
     */
    public $parent;

    /**
     * @var array
     */
    public $store;

    /**
     * @var integer
     */
    public $depth;
}
