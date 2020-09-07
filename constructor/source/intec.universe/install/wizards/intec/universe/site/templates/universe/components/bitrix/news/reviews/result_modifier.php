<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LIST_FIELD_CODE' => [],
    'DETAIL_FIELD_CODE' => []
], $arParams);

if (!Type::isArray($arParams['LIST_FIELD_CODE']))
    $arParams['LIST_FIELD_CODE'] = [];

if (!Type::isArray($arParams['DETAIL_FIELD_CODE']))
    $arParams['DETAIL_FIELD_CODE'] = [];

if (!ArrayHelper::isIn('DETAIL_PICTURE', $arParams['LIST_FIELD_CODE']))
    $arParams['LIST_FIELD_CODE'][] = 'DETAIL_PICTURE';

if (!ArrayHelper::isIn('PREVIEW_PICTURE', $arParams['DETAIL_FIELD_CODE']))
    $arParams['DETAIL_FIELD_CODE'][] = 'PREVIEW_PICTURE';