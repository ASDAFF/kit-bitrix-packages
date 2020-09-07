<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $webForm
 */

if (!Loader::includeModule('form'))
    return;


$webForm = CForm::GetByID($arParams['WEB_FORM_ID'])->GetNext();