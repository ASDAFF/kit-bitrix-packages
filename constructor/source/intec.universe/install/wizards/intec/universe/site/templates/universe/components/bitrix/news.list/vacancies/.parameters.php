<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('iblock'))
    return;

$arForms = array();
$arFormFields = array();

if (Loader::includeModule('form')) {
    include(__DIR__.'/parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite.php');
}

$arTemplateParameters = array();

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];
$iIBlockId = $arCurrentValues['IBLOCK_ID'];

$arTemplateParameters['CURRENCY'] = array(
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_CURRENCY')
);

$arTemplateParameters['FORM_SUMMARY_DISPLAY'] = array(
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_FORM_SUMMARY_DISPLAY')
);

if (!empty($iIBlockId)) {
    $arProperties = array();
    $arPropertiesString = array();
    $rsProperties = CIBlockProperty::GetList(array(), array(
        'IBLOCK_ID' => $iIBlockId,
        'ACTIVE' => 'Y'
    ));

    while ($arProperty = $rsProperties->GetNext()) {
        if (empty($arProperty['CODE']))
            continue;

        $sName = '['.$arProperty['CODE'].'] '.$arProperty['NAME'];

        if ($arProperty['PROPERTY_TYPE'] == 'S')
            $arPropertiesString[$arProperty['CODE']] = $sName;

        $arProperties[$arProperty['CODE']] = $arProperty;
    }

    $arTemplateParameters['PROPERTY_SALARY'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_PROPERTY_SALARY'),
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );
}

if ($arCurrentValues['FORM_SUMMARY_DISPLAY'] == 'Y') {
    $arTemplateParameters['FORM_SUMMARY_ID'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_FORM_SUMMARY'),
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    );

    if (!empty($arCurrentValues['FORM_SUMMARY_ID'])) {
        $arTemplateParameters['PROPERTY_FORM_SUMMARY_VACANCY'] = array(
            'PARENT' => 'DATA_SOURCE',
            'TYPE' => 'LIST',
            'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_PROPERTY_FORM_SUMMARY_VACANCY'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );
    }
}

$arTemplateParameters['CONSENT_URL'] = array(
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('N_L_VACANCIES_PARAMETERS_CONSENT_URL')
);