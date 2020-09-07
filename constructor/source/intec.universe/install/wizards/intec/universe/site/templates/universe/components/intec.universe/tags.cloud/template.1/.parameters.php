<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['HEADER_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_TAGS_CLOUD_TEMP1_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['HEADER_SHOW']) {
    $arTemplateParameters['HEADER_TEXT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_TAGS_CLOUD_TEMP1_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_TAGS_CLOUD_TEMP1_HEADER_TEXT_DEFAULT')
    );
}