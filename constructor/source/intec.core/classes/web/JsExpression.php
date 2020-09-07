<?php
namespace intec\core\web;

use intec\core\base\BaseObject;

class JsExpression extends BaseObject
{
    public $expression;

    public function __construct($expression, $config = [])
    {
        $this->expression = $expression;
        parent::__construct($config);
    }

    public function __toString()
    {
        return $this->expression;
    }
}
