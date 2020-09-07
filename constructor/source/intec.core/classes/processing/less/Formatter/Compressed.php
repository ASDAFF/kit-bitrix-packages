<?php
namespace intec\core\processing\less\Formatter;

use intec\core\processing\less\Formatter;

class Compressed extends Formatter {
    public $disableSingle = true;
    public $open = "{";
    public $selectorSeparator = ",";
    public $assignSeparator = ":";
    public $break = "";
    public $compressColors = true;

    public function indentStr($n = 0) {
        return "";
    }
}