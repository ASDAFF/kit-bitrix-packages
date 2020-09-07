<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

if (empty($arParams['HIDE_USER_INFO']))
    $arParams['HIDE_USER_INFO'] = [];

if (!Type::isArray($arParams['HIDE_USER_INFO']))
    $arParams['HIDE_USER_INFO'] = [];