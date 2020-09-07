<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return false;

if (Loader::includeModule('form')) {
    include('modifier/base.php');
} elseif (Loader::includeModule('intec.startshop')) {
    include('modifier/lite.php');
} else {
    return;
}

$arResult['WEB_FORM'] = $arResult['WEB_FORM']->GetNext();
$arResult['TEXT'] = [
    'TITLE' => $arParams['TITLE'],
    'DESCRIPTION' => $arParams['DESCRIPTION'],
    'BUTTON' => $arParams['BUTTON'],
    'FORM' => $arParams['FORM']
];

if ($arParams['GRAB_DATA'] == 'Y' && !empty($arResult['WEB_FORM'])) {
    $arResult['TEXT']['TITLE'] = $arResult['WEB_FORM']['NAME'];
    $arResult['TEXT']['DESCRIPTION'] = $arResult['WEB_FORM']['DESCRIPTION'];
    $arResult['TEXT']['BUTTON'] = $arResult['WEB_FORM']['NAME'];
    $arResult['TEXT']['FORM'] = $arResult['WEB_FORM']['NAME'];
}

if (empty($arResult['TEXT']['TITLE']))
    $arResult['TEXT']['TITLE'] = GetMessage('W_WEB_FORM_2_TITLE');

if (empty($arResult['TEXT']['DESCRIPTION']))
    $arResult['TEXT']['DESCRIPTION'] = GetMessage('W_WEB_FORM_2_DESCRIPTION');

if (empty($arResult['TEXT']['BUTTON']))
    $arResult['TEXT']['BUTTON'] = GetMessage('W_WEB_FORM_2_BUTTON');

if (empty($arResult['TEXT']['FORM']))
    $arResult['TEXT']['FORM'] = GetMessage('W_WEB_FORM_2_FORM');