<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 22-Jan-18
 * Time: 2:15 PM
 */

namespace Sotbit\Origami\Config;


/**
 * Class Widget
 * @package Sotbit\Origami\Config
 * @author Sergey Danilkin <s.danilkin@kit.ru>
 */
abstract class Widget
{
	protected $code;
	protected $currentValue;
	protected $values = array();
	protected $settings = array(
		"COLSPAN" => array(
			0 => 1,
			1 => 1
		)
	);
	/**
	 * @var string
	 */
	protected $site = '';


	public abstract function show();

	public function __construct(
		$code = '',
		$settings = array()
	)
	{
		$this->setCode($code);
		$this->setSettings($settings);
	}

	protected function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @param $id
	 * @param $value
	 */
	protected function setValue(
		$id,
		$value
	)
	{
		$this->values[$id] = $value;
	}

	public function setCurrentValue($value)
	{
		$this->currentValue = $value;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function prepareRequest(&$request)
	{

	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return $this->settings;
	}
	/**
	 * @return mixed
	 */
	public function getSetting($code)
	{
		return $this->settings[$code];
	}

	/**
	 * @param array $settings
	 */
	public function setSettings(array $settings)
	{
		$this->settings = array_merge($this->settings,$settings);
	}

	public function getValues()
	{
		return $this->values;
	}

	public function setValues($values = array())
	{

	}

	public function getCurrentValue()
	{
		return $this->currentValue;
	}

	/**
	 * @return string
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param string $site
	 */
	public function setSite($site)
	{
		$this->site = $site;
	}
}

?>