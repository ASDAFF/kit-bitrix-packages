<?php
namespace intec\core\processing\scss\Formatter;

use intec\core\processing\scss\Formatter;
use intec\core\processing\scss\Formatter\OutputBlock;

/**
 * Expanded formatter
 */
class Expanded extends Formatter
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->indentLevel = 0;
        $this->indentChar = '  ';
        $this->break = "\n";
        $this->open = ' {';
        $this->close = '}';
        $this->tagSeparator = ', ';
        $this->assignSeparator = ': ';
        $this->keepSemicolons = true;
    }

    /**
     * {@inheritdoc}
     */
    protected function indentStr()
    {
        return str_repeat($this->indentChar, $this->indentLevel);
    }

    /**
     * {@inheritdoc}
     */
    protected function blockLines(OutputBlock $block)
    {
        $inner = $this->indentStr();

        $glue = $this->break . $inner;

        foreach ($block->lines as $index => $line) {
            if (substr($line, 0, 2) === '/*') {
                $block->lines[$index] = preg_replace('/(\r|\n)+/', $glue, $line);
            }
        }

        echo $inner . implode($glue, $block->lines);

        if (empty($block->selectors) || ! empty($block->children)) {
            echo $this->break;
        }
    }
}
