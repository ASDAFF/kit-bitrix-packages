<?php
namespace Sotbit\Origami\Internals;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class BlockTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CODE string(255) mandatory
 * <li> PART string(255) mandatory
 * <li> SORT int mandatory
 * <li> ACTIVE string(1) mandatory
 * <li> PUBLIC string(1) mandatory
 * <li> CREATED_BY int manshowdatory
 * <li> DATE_CREATE datetime mandatory
 * <li> MODIFIED_BY int mandatory
 * <li> DATE_MODIFY datetime optional
 * </ul>
 *
 * @package Sotbit\Origami
 **/

class BlockTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'sotbit_origami_blocks';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('BLOCKS_ENTITY_ID_FIELD'),
			),
			'CODE' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateCode'),
				'title' => Loc::getMessage('BLOCKS_ENTITY_CODE_FIELD'),
			),
			'PART' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validatePart'),
				'title' => Loc::getMessage('BLOCKS_ENTITY_PART_FIELD'),
			),
			'SORT' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('BLOCKS_ENTITY_SORT_FIELD'),
			),
			'ACTIVE' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateActive'),
				'title' => Loc::getMessage('BLOCKS_ENTITY_ACTIVE_FIELD'),
			),
			'CREATED_BY' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('BLOCKS_ENTITY_CREATED_BY_FIELD'),
			),
			'DATE_CREATE' => array(
				'data_type' => 'datetime',
				'required' => true,
				'title' => Loc::getMessage('BLOCKS_ENTITY_DATE_CREATE_FIELD'),
			),
			'MODIFIED_BY' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('BLOCKS_ENTITY_MODIFIED_BY_FIELD'),
			),
			'DATE_MODIFY' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('BLOCKS_ENTITY_DATE_MODIFY_FIELD'),
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
			new Main\Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * @return array
	 * @throws Main\ArgumentTypeException
	 */
	public static function validatePart()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * @return array
	 * @throws Main\ArgumentTypeException
	 */
	public static function validateActive()
	{
		return array(
			new Main\Entity\Validator\Length(null, 1),
		);
	}
}