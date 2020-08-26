<?php
namespace Sotbit\Origami\Config;

/**
 * Class Group
 * @package Sotbit\Origami\Config
 * @author Sergey Danilkin <s.danilkin@sotbit.ru>
 */
class Group
{
	protected $widgets;
	protected $code;
	protected $settings = array('COLSPAN' => 2);
	public function __construct($code, $settings = array())
	{
		$this->widgets = new \Sotbit\Origami\Collection();
		$this->setCode($code);
		$this->settings = array_merge($this->settings,$settings);
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param mixed $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @return \Sotbit\Origami\Collection
	 */
	public function getWidgets()
	{
		return $this->widgets;
	}

	/**
	 * @return mixed
	 */
	public function getSetting($code)
	{
		return $this->settings[$code];
	}

}