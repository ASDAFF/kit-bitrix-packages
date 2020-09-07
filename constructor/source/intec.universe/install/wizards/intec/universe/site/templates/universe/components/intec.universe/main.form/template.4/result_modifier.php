<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'TITLE' => Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_TITLE_DEFAULT'),
    'DESCRIPTION' => null,
    'IMAGE' => null,
    'CONSULTANT_NAME' => null,
    'CONSULTANT_POST' => null,
    'FORMS_1_ID' => null,
    'FORMS_2_ID' => null,
    'FORMS_1_NAME' => null,
    'FORMS_2_NAME' => null,
    'FORMS_1_BUTTON' => null,
    'FORMS_2_BUTTON' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arResult['TITLE'] = [
    'TEXT' => $arParams['~TITLE']
];

$arResult['DESCRIPTION'] = [
    'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
    'TEXT' => $arParams['~DESCRIPTION']
];

if (empty($arResult['TITLE']['TEXT']))
    $arResult['TITLE']['TEXT'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_TITLE_DEFAULT');

if (empty($arResult['DESCRIPTION']['TEXT']))
    $arResult['DESCRIPTION']['SHOW'] = false;

$arResult['IMAGE'] = [
    'SHOW' => $arParams['IMAGE_SHOW'] === 'Y',
    'SRC' => $arParams['IMAGE']
];

if (!empty($arResult['IMAGE']['SRC']))
    $arResult['IMAGE']['SRC'] = StringHelper::replaceMacros($arResult['IMAGE']['SRC'], $arMacros);

if (empty($arResult['IMAGE']['SRC']))
    $arResult['IMAGE']['SHOW'] = false;

$arResult['CONSULTANT'] = [
    'NAME' => $arParams['CONSULTANT_NAME'],
    'POST' => $arParams['CONSULTANT_POST'],
];

if (empty($arResult['CONSULTANT']['NAME']))
    $arResult['CONSULTANT']['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_NAME_DEFAULT');

if (empty($arResult['CONSULTANT']['POST']))
    $arResult['CONSULTANT']['POST'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_CONSULTANT_POST_DEFAULT');

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
    $arResult['FORMS'][0]['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_NAME_DEFAULT');

if (empty($arResult['FORMS'][0]['BUTTON']))
    $arResult['FORMS'][0]['BUTTON'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_1_BUTTON_DEFAULT');

if (empty($arResult['FORMS'][1]['ID']))
    $arResult['FORMS'][1]['SHOW'] = false;

if (empty($arResult['FORMS'][1]['NAME']))
    $arResult['FORMS'][1]['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_NAME_DEFAULT');

if (empty($arResult['FORMS'][1]['BUTTON']))
    $arResult['FORMS'][1]['BUTTON'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_4_FORMS_2_BUTTON_DEFAULT');