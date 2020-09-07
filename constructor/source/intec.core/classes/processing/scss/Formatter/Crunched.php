<?php
namespace intec\core\processing\scss\Formatter;

use intec\core\processing\scss\Formatter;
use intec\core\processing\scss\Formatter\OutputBlock;

/**
 * Crunched formatter
 */
class Crunched extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '  ';
        $this->break = '';
        $this->open = '{';
        $this->close = '}';
        $this->tagSeparator = ',';
        $this->assignSeparator = ':';
        $this->keepSemicolons = false;
    }

    /**
     * {@inheritdoc}
     */
    public function blockLines(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $glue = $this->break . $inner;

        foreach ($block->lines as $index => $line) {
            if (substr($line, 0, 2) === '/*') {
                unset($block->lines[$index]);
            }
        }

        echo $inner . implode($glue, $block->lines);

        if (! empty($block->children)) {
            echo $this->break;
        }
    }
}
