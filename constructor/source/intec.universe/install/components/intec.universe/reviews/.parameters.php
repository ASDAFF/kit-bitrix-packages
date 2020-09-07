<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
	$arIBlock[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

$parameters = array(
	'IBLOCK_TYPE' => array(
		'NAME' => GetMessage('IBLOCK_TYPE'),
		'TYPE' => 'LIST',
		'VALUES' => $arIBlockType,
		'REFRESH' => 'Y',
	),
	'IBLOCK_ID' => array(
		'NAME' => GetMessage('IBLOCK'),
		'TYPE' => 'LIST',
		'ADDITIONAL_VALUES' => 'Y',
		'VALUES' => $arIBlock,
		'REFRESH' => 'Y',
	),
	'ELEMENT_ID' => array(
		'NAME' => GetMessage('ELEMENT_ID'),
		'TYPE' => 'STRING'
	),
	'DISPLAY_REVIEWS_COUNT' => array(
		'NAME' => GetMessage('DISPLAY_REVIEWS_COUNT'),
		'TYPE' => 'STRING'
	)
);

$mailEventsList = array();
$mailEvents = CEventType::GetList(
	array(
		'TYPE_ID' => 'WF_NEW_IBLOCK_ELEMENT'
	)
);
while ($row = $mailEvents->Fetch()) {
	$mailEventsList[$row['CODE']] = $row['NAME'];
}
$parameters['MAIL_EVENT'] = array(
	'NAME' => GetMessage('MAIL_EVENT'),
	'TYPE' => 'LIST',
	'VALUES' => $mailEventsList,
);
unset($mailEvents, $mailEventsList);

if (!empty($arCurrentValues['IBLOCK_ID'])) {
	$infoblockPropertiesList = array();
	$infoblockProperties = CIBlockProperty::GetList(array(), array(
		'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
		'PROPERTY_TYPE' => 'E',
		'ACTIVE' => 'Y'
	));
	while ($row = $infoblockProperties->Fetch()) {
		$infoblockPropertiesList[$row['CODE']] = $row['NAME'];
	}
	$parameters['PROPERTY_ELEMENT_ID'] = array(
		'NAME' => GetMessage('PROPERTY_ELEMENT_ID'),
		'TYPE' => 'LIST',
		'VALUES' => $infoblockPropertiesList,
	);
	unset($infoblockProperties, $infoblockPropertiesList);
}

$parameters['USE_CAPTCHA'] = array(
	'NAME' => GetMessage('USE_CAPTCHA'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);

$arComponentParameters = array(
	'PARAMETERS' => $parameters
);