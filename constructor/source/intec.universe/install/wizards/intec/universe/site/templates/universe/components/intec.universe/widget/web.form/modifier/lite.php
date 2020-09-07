<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $webForm
 */

if (!Loader::includeModule('intec.startshop'))
    return;


$webForm = CStartShopForm::GetByID($arParams['WEB_FORM_ID'])->GetNext();

$webForm['NAME'] = ArrayHelper::getValue($webForm, ['LANG', LANGUAGE_ID, 'NAME']);
$webForm['BUTTON'] = ArrayHelper::getValue($webForm, ['LANG', LANGUAGE_ID, 'BUTTON']);
$webForm['DESCRIPTION'] = ArrayHelper::getValue($arParams, 'FORM_TEXT');

