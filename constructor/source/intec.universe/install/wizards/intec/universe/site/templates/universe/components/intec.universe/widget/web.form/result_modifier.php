<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return false;


$webForm = [];

if (Loader::includeModule('form')) {
    include('modifier/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include('modifier/lite.php');
}


$arResult['WEB_FORM'] = $webForm;