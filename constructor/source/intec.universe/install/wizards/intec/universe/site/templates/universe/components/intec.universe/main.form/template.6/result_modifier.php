<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'TITLE' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_TITLE_DEFAULT'),
    'DESCRIPTION' => null,
    'DESCRIPTION_SHOW' => 'N',
    'FORM_1_ID' => null,
    'FORM_2_ID' => null,
    'FORM_1_NAME' => null,
    'FORM_2_NAME' => null,
    'FORM_1_BUTTON' => null,
    'FORM_2_BUTTON' => null,
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arResult['TITLE'] = [
    'TEXT' => $arParams['~TITLE']
];

if (empty($arResult['TITLE']['TEXT']))
    $arResult['TITLE']['TEXT'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_TITLE_DEFAULT');

$arResult['DESCRIPTION'] = [
    'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
    'TEXT' => $arParams['~DESCRIPTION']
];

if (empty($arResult['DESCRIPTION']['TEXT']))
    $arResult['DESCRIPTION']['SHOW'] = false;

$arResult['FORMS'] = [[
    'SHOW' => true,
    'ID' => $arParams['FORMS_1_ID'],
    'NAME' => $arParams['FORMS_1_NAME'],
    'BUTTON' => $arParams['FORMS_1_BUTTON'],
], [
    'SHOW' => true,
    'ID' => $arParams['FORMS_2_ID'],
    'NAME' => $arParams['FORMS_2_NAME'],
    'BUTTON' => $arParams['FORMS_2_BUTTON'],
]];

if (empty($arResult['FORMS'][0]['ID']))
    $arResult['FORMS'][0]['SHOW'] = false;

if (empty($arResult['FORMS'][0]['NAME']))
    $arResult['FORMS'][0]['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_NAME_DEFAULT');

if (empty($arResult['FORMS'][0]['BUTTON']))
    $arResult['FORMS'][0]['BUTTON'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_1_BUTTON_DEFAULT');

if (empty($arResult['FORMS'][1]['ID']))
    $arResult['FORMS'][1]['SHOW'] = false;

if (empty($arResult['FORMS'][1]['NAME']))
    $arResult['FORMS'][1]['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_NAME_DEFAULT');

if (empty($arResult['FORMS'][1]['BUTTON']))
    $arResult['FORMS'][1]['BUTTON'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_6_FORMS_2_BUTTON_DEFAULT');