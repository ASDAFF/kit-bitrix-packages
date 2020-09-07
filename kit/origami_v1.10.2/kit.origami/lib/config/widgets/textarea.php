<?php

namespace Kit\Origami\Config\Widgets;

use Kit\Origami\Config\Widget;

class Textarea extends Widget
{
    protected $defaultValue;
    protected $defaultCols = 60;

    public function __construct($code, $defaultValue = '', $settings = array())
    {
        $this->setCode($code);
        $this->setSettings($settings);
        $this->defaultValue = $defaultValue;
    }

    public function show()
    {

        $show = "<textarea name=\"{$this->getCode()}\"";

        if ($this->settings['cols'] > 0)
            $show .= " cols='{$this->settings['cols']}'>";
        else
            $show .= " cols='60'>";

        if ($this->currentValue)
            $show .= $this->getCurrentValue();
        else
            $show .= $this->defaultValue;

        $show .= "</textarea>";

        echo $show;
    }
}
