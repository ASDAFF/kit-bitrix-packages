<?php
namespace Sotbit\Origami\Config;

/**
 * Class Tab
 * @package Sotbit\Origami\Config
 * @author Sergey Danilkin <s.danilkin@kit.ru>
 */
class Tab
{
	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @var \Sotbit\Origami\Collection
	 */
	protected $groups;

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $site = '';

	/**
	 * Tab constructor.
	 * @param $code
	 */
	public function __construct($code)
	{
		$this->groups = new \Sotbit\Origami\Collection();
		$this->setCode($code);
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
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
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