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

$arTemplateParameters['FORMS_CHEAPER_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['FORMS_CHEAPER_SHOW'] === 'Y') {
    $arTemplateParameters['FORMS_CHEAPER_ID'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms->asArray($hFormsList),
        'ADDITIONAL_VALUES' => 'Y'
    );
}

if (!empty($arCurrentValues['FORMS_CHEAPER_ID'])) {
    $arFields = Arrays::fromDBResult(CStartShopFormProperty::GetList([], [
        'FORM' => $arCurrentValues['FORMS_CHEAPER_ID']
    ]))->indexBy('ID');
    $arTemplates = Arrays::from(CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new'))->indexBy('NAME');
}

if (!empty($arCurrentValues['FORMS_CHEAPER_ID'])) {
    $arTemplateParameters['FORMS_CHEAPER_PROPERTY_PRODUCT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_PROPERTY_PRODUCT'),
        'TYPE' => 'LIST',
        'VALUES' => $arFields->asArray($hFieldsList),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['FORMS_CHEAPER_TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates->asArray($hTemplatesList),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

unset($arForms, $arFields, $arTemplates, $hFormsList, $hFieldsList, $hTemplatesList);