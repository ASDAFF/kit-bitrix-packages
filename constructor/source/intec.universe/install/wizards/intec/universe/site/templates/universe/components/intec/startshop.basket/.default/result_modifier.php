<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

$arDefaultParams = array(
    'USE_ADAPTABILITY' => 'N',
    'USE_ITEMS_PICTURES' => 'Y',
    'USE_BUTTON_CLEAR' => 'N',
    'USE_BUTTON_BASKET' => 'N',
    'USE_SUM_FIELDS' => 'N',
    'VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA' => 'N',
    'URL_RULES_OF_PERSONAL_DATA_PROCESSING' => ''
);

$arParams = array_merge($arDefaultParams, $arParams);
