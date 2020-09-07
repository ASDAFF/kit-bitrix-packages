<?php
namespace Sotbit\Origami\Config\Widgets;


use Sotbit\Origami\Config\Widget;

class Str extends Widget
{
	public function show()
	{
		echo '<input type="text" name="'.$this->getCode().'" value="'.$this->getCurrentValue().'" />';
	}
}
?>