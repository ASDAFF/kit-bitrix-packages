<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return false;

$arParams = ArrayHelper::merge([
    'WIDE' => 'N',
    'FORM_ID' => null,
    'FORM_TEMPLATE' => null,
    'TITLE' => null,
    'DESCRIPTION' => null,
    'BUTTON_TEXT' => null,
    'FORM_TITLE' => null,
    'CONSENT_URL' => null

], $arParams);

if (Loader::includeModule('form')) {
    include('modifier/base.php');
} elseif (Loader::includeModule('intec.startshop')) {
    include('modifier/lite.php');
} else {
    return;
}

$arResult['VISUAL'] = [
    'WIDE' => $arParams['WIDE'] === 'Y',
    'BORDERS' => $arParams['WIDE'] !== 'Y' ? ArrayHelper::fromRange(['squared', 'rounded'], $arParams['BORDER_STYLE']) : 'squared'
];

$arResult['WEB_FORM'] = $arResult['WEB_FORM']->GetNext();
$arResult['FORM'] = [
    'TITLE' => !empty($arParams['TITLE']) ? $arParams['TITLE'] : $arResult['WEB_FORM']['NAME'],
    'DESCRIPTION' => !empty($arParams['DESCRIPTION']) ? $arParams['DESCRIPTION'] : $arResult['WEB_FORM']['DESCRIPTION'],
    'BUTTON_TEXT' => !empty($arParams['BUTTON_TEXT']) ? $arParams['BUTTON_TEXT'] : $arResult['WEB_FORM']['NAME'],
    'POPUP_TITLE' => !empty($arParams['POPUP_TITLE']) ? $arParams['POPUP_TITLE'] : $arResult['WEB_FORM']['NAME'],
];

if (empty($arResult['FORM']['TITLE']))
    $arResult['FORM']['TITLE'] = Loc::GetMessage('C_WIDGET_FORM_4_TITLE');

if (empty($arResult['FORM']['DESCRIPTION']))
    $arResult['FORM']['DESCRIPTION'] = Loc::GetMessage('C_WIDGET_FORM_4_DESCRIPTION');

if (empty($arResult['FORM']['BUTTON_TEXT']))
    $arResult['FORM']['BUTTON_TEXT'] = Loc::GetMessage('C_WIDGET_FORM_4_BUTTON_TEXT');

if (empty($arResult['FORM']['POPUP_TITLE']))
    $arResult['FORM']['POPUP_TITLE'] = Loc::GetMessage('C_WIDGET_FORM_4_POPUP_TITLE');