<?php
namespace Kit\Origami\Internals;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Entity;

Loc::loadMessages(__FILE__);

/**
 * Class OptionsTable
 *
 * Fields:
 * <ul>
 * <li> NAME string(50) mandatory
 * <li> VALUE string optional
 * <li> SITE_ID string(2) optional
 * </ul>
 *
 * @package Bitrix\Techno
 **/

class OptionsTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'kit_origami_options';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'CODE' => array(
				'data_type' => 'string',
				'primary' => true,
				'validation' => array(__CLASS__, 'validateCode'),
				'title' => Loc::getMessage(\KitOrigami::moduleId.'_OPTIONS_ENTITY_CODE_FIELD'),
			),
			'VALUE' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage(\KitOrigami::moduleId.'_OPTIONS_ENTITY_VALUE_FIELD'),
			),
			'SITE_ID' => array(
				'data_type' => 'string',
				'primary' => true,
				'validation' => array(__CLASS__, 'validateSiteId'),
				'title' => Loc::getMessage(\KitOrigami::moduleId.'_OPTIONS_ENTITY_SITE_ID_FIELD'),
			),
		);
	}

	/**
	 * @return array
	 * @throws Main\ArgumentTypeException
	 */
	public static function validateCode()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}

	/**
	 * @return array
	 * @throws Main\ArgumentTypeException
	 */
	public static function validateSiteId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 2),
		);
	}
	public static function OnAdd(Entity\Event $event)
	{
		self::getEntity()->cleanCache();
	}
	public static function OnUpdate(Entity\Event $event)
	{
		self::getEntity()->cleanCache();
	}
	public static function OnDelete(Entity\Event $event)
	{
		self::getEntity()->cleanCache();
	}
}
?>