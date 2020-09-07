<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'FORMS_CALL_SHOW' => 'N',
    'FORMS_CALL_ID' => null,
    'FORMS_CALL_TEMPLATE' => '.default',
    'FORMS_CALL_TITLE' => null
], $arParams);

$arResult['FORMS'] = [];
$arResult['FORMS']['CALL'] = [
    'SHOW' => $arParams['FORMS_CALL_SHOW'] === 'Y',
    'ID' => $arParams['FORMS_CALL_ID'],
    'TEMPLATE' => $arParams['FORMS_CALL_TEMPLATE'],
    'TITLE' => $arParams['FORMS_CALL_TITLE']
];

if ($arResult['FORMS']['CALL']['SHOW'] && empty($arResult['FORMS']['CALL']['ID']))
    $arResult['FORMS']['CALL']['SHOW'] = false;

if (empty($arResult['FORMS']['CALL']['TITLE']))
    $arResult['FORMS']['CALL']['TITLE'] = Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_TITLE');