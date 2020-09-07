<?php
namespace intec\core\processing\less\Formatter;

use intec\core\processing\less\Formatter;

class JavaScript extends Formatter {
    public $disableSingle = true;
    public $breakSelectors = true;
    public $assignSeparator = ": ";
    public $selectorSeparator = ",";
}