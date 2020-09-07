<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

/** Параметры заголовка */
$sHeaderText = ArrayHelper::getValue($arParams, 'HEADER_TEXT');
$sHeaderText = trim($sHeaderText);
$bHeaderShow = ArrayHelper::getValue($arParams, 'HEADER_SHOW');
$bHeaderShow = $bHeaderShow == 'Y' && !empty($sHeaderText);

$arResult['HEADER_BLOCK'] = [
    'SHOW' => $bHeaderShow,
    'TEXT' => Html::encode($sHeaderText)
];