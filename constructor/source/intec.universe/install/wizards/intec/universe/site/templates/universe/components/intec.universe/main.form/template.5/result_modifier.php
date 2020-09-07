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
    'TITLE' => null,
    'DESCRIPTION' => null,
    'DESCRIPTION_SHOW' => 'N',
    'IMAGE' => null,
    'IMAGE_SHOW' => 'N',
    'FORM_ID' => null,
    'FORM_NAME' => null,
    'FORM_BUTTON' => null,
    'BACKGROUND_COLOR' => '',
    'BACKGROUND_IMAGE' => null,
    'TEXT_COLOR' => null,
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/',
    'TEMPLATE_PATH' => $this->GetFolder().'/'
];

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['TEXT'] = [
    'COLOR' => $arParams['TEXT_COLOR']
];

$arResult['BACKGROUND'] = [
    'COLOR' => $arParams['BACKGROUND_COLOR'],
    'IMAGE' => StringHelper::replaceMacros($arParams['BACKGROUND_IMAGE'], $arMacros)
];

$arResult['TITLE'] = [
    'TEXT' => $arParams['~TITLE']
];

$arResult['DESCRIPTION'] = [
    'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
    'TEXT' => $arParams['~DESCRIPTION']
];

if (empty($arResult['TITLE']['TEXT']))
    $arResult['TITLE']['TEXT'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_TITLE_DEFAULT');

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


$arResult['FORM'] = [
    'SHOW' => true,
    'ID' => $arParams['FORM_ID'],
    'NAME' => $arParams['FORM_NAME'],
    'BUTTON' => $arParams['FORM_BUTTON'],
];

if (empty($arResult['FORM']['ID']))
    $arResult['FORM']['SHOW'] = false;

if (empty($arResult['FORM']['NAME']))
    $arResult['FORM']['NAME'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORMS_1_NAME_DEFAULT');

if (empty($arResult['FORM']['BUTTON']))
    $arResult['FORM']['BUTTON'] = Loc::getMessage('C_MAIN_FORM_TEMPLATE_5_FORMS_1_BUTTON_DEFAULT');