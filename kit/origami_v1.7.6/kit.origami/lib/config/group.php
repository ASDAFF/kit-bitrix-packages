<?php
namespace Kit\Origami\Config;

/**
 * Class Group
 * @package Kit\Origami\Config
 * 
 */
class Group
{
	protected $widgets;
	protected $code;
	protected $settings = array('COLSPAN' => 2);
	public function __construct($code, $settings = array())
	{
		$this->widgets = new \Kit\Origami\Collection();
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
	 * @return \Kit\Origami\Collection
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