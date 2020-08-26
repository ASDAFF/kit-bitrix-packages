<?php
namespace Kit\Origami\Config\Widgets;

use Kit\Origami\Config\Widget;

class Select extends Widget
{
    protected $disable = [];
    protected $multiple = false;
    protected $canEmpty = false;
    public function setDisable($keys = array())
    {
        $this->disable = $keys;
    }
    public function changeName($key, $addValue)
    {
        $this->values[$key] .= $addValue;
    }
	public function setValues($values = array())
	{
	    if($this->isCanEmpty()){
            $this->setValue('','-');
        }
		foreach($values as $key => $value)
		{
			$this->setValue($key,$value);
		}
	}
	public function setMultiple($value = false)
    {
        $this->multiple = $value;
    }
	public function show()
	{
	    $show = '<select name="'.$this->getCode();
        if($this->multiple)
        {
            $show .='[]';
        }
        $show .= '"';
        if($this->multiple)
        {
            $show .= 'multiple';
        }
		if($this->settings['refresh'] == 'Y')
		{
			$show .= 'onchange="submit();"';
		}
		$show .= '>';
		$isSelected = false;
		foreach($this->values as $key => $value)
		{
			$show .= '<option value="'.$key.'"';
			if(!$isSelected && is_array($this->getCurrentValue()))
            {
                if (in_array($key,$this->getCurrentValue())) {
                    $show .= ' selected';
                    //$isSelected = true;
                }
            }
			elseif(!$isSelected) {
                if ($key == $this->getCurrentValue()) {
                    $show .= ' selected';
                    $isSelected = true;
                }
            }
			if(in_array($key,$this->disable))
			{
				$show .= ' disabled';
			}
			$show .= '>'.$value.'</option>';
		}

		$show .= '</select>';
		echo $show;
	}

	public function prepareRequest(&$request)
    {
        if($this->multiple && is_array($request[$this->getCode()]))
        {
            $request[$this->getCode()] = serialize($request[$this->getCode()]);
        }
    }

    public function setCurrentValue($value)
    {
        if($this->multiple)
        {
            $value = unserialize($value);
            if(!is_array($value))
            {
                $value = [];
            }
        }
        $this->currentValue = $value;
    }

    /**
     * @return bool
     */
    public function isCanEmpty()
    {
        return $this->canEmpty;
    }

    /**
     * @param bool $canEmpty
     */
    public function setCanEmpty($canEmpty)
    {
        $this->canEmpty = $canEmpty;
    }
}
?>