<?php
namespace Sotbit\Origami\Config\Widgets;

use Sotbit\Origami\Config\Widget;

class SelectAdd extends Widget
{
	public function setValues($values = array())
	{
		foreach($values as $key => $value)
		{
			$this->setValue($key,$value);
		}
	}
	public function show()
	{
		$custom = true;
		$show = '<select name="'.$this->getCode().'">';
		foreach($this->values as $key => $value)
		{
			$show .= '<option value="'.$key.'"';
			if($key == $this->getCurrentValue())
			{
				$custom = false;
				$show .= ' selected';
			}
			$show .= '>'.$value.'</option>';
		}

		$show .= '</select><br>
		<input type="text" name="'.$this->getCode().'_ADD" value="';
		if($custom)
		{
			$show .= $this->getCurrentValue();
		}
		$show .='">';
		echo $show;
	}
	public function prepareRequest(&$request)
	{
		if($request[$this->getCode().'_ADD'])
		{
			$request[$this->getCode()] = $request[$this->getCode().'_ADD'];
		}
	}
}
?>