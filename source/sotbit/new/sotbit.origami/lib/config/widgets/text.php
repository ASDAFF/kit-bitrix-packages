<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 22-Jan-18
 * Time: 2:15 PM
 */

namespace Sotbit\Origami\Config\Widgets;


use Sotbit\Origami\Config\Widget;

class Text extends Widget
{
	public function __construct($code, $settings = array())
	{
		$this->setCode($code);
		$this->setSettings($settings);
	}
	public function setValues($values = array())
	{

	}
	public function show()
	{

	}
}
?>