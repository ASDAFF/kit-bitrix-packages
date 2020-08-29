<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 23-Jan-18
 * Time: 9:59 AM
 */

namespace Kit\Origami\Config\Widgets;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class Variables
 * @package Kit\Origami\Config\Widgets
 * @author Sergey Danilkin <s.danilkin@kit.ru>
 */
class Variables extends Text
{
	/**
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function setValues($values = array())
	{
		$settings = $this->getSettings();

		$tags = \KitOrigami::getTags(array($settings['SITE_ID']));
		file_put_contents(dirname(__FILE__).'/log.log', print_r($tags, true), FILE_APPEND);
		foreach($tags as $tag)
		{
			$this->setValue($tag['CODE'], $tag['NAME']);
		}
	}

	/**
	 * @param $id
	 * @param $value
	 */
	public function setValue($id,$value)
	{
		$this->values[$id] = $value;
	}

	/**
	 *
	 */
	public function show()
	{
		$values = $this->getValues();
		if($values)
		{
			echo '<tr><td style="text-align: center;" width="25%"><b>';
			echo Loc::getMessage(\KitOrigami::moduleId.'_WIDGET_'.$this->getCode().'_NAME');
			echo '</b></td><td style="text-align: center;" width="25%"><b>';
			echo Loc::getMessage(\KitOrigami::moduleId.'_WIDGET_'.$this->getCode().'_CODE');
			echo '</b></td><td style="text-align: center;" width="50%"><b>';
			echo Loc::getMessage(\KitOrigami::moduleId.'_WIDGET_'.$this->getCode().'_VARIABLE');
			echo '</b></td></tr>';
		}
		foreach($values as $code => $name)
		{
			echo '
				<tr><td style="text-align: center;" width="25%">',$name,'</td>
				<td style="text-align: center;" width="25%">',\KitOrigami::genCodeVariable($code),'</td>
				<td style="text-align: center;" width="50%">$_SESSION["KIT_REGIONS"]["',$code,'"]</td></tr>';
		}
	}

}