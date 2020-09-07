<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

$arForms = Arrays::fromDBResult(CStartShopForm::GetList())->indexBy('ID');
$arFields = null;
$arTemplates = null;

$hFormsList = function ($sKey, $arProperty) {
    return [
        'key' => $arProperty['ID'],
        'value' => '['.$arProperty['ID'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
    ];
};
$hFieldsList = function ($sKey, $arProperty) {
    return [
        'key' => $arProperty['CODE'],
        'value' => '['.$arProperty['CODE'].'] '.$arProperty['LANG'][LANGUAGE_ID]['NAME']
    ];
};
$hTemplatesList = function ($sKey, $arProperty) {
    return [
        'key' => $arProperty['NAME'],
        'value' => $arProperty['NAME'].'('.$arProperty['TEMPLATE'].')'
    ];
};

if (!empty($arCurrentValues['FORM_ID'])) {
    $arFields = Arrays::fromDBResult(CStartShopFormProperty::GetList([], [
        'FORM' => $arCurrentValues['FORM_ID']
    ]))->indexBy('ID');
    $arTemplates = Arrays::from(CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new'))->indexBy('NAME');
}

$arTemplateParameters['FORM_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms->asArray($hFormsList),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arCurrentValues['FORM_ID'])) {
    $arTemplateParameters['FORM_PROPERTY_PRODUCT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_FORM_PROPERTY_PRODUCT'),
        'TYPE' => 'LIST',
        'VALUES' => $arFields->asArray($hFieldsList),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['FORM_TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_FORM_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates->asArray($hTemplatesList),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

unset($arForms, $arFields, $arTemplates, $hFormsList, $hFieldsList, $hTemplatesList);