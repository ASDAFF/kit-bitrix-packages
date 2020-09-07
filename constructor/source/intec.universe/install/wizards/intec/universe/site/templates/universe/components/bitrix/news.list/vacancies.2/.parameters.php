<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('intec.core'))
    return;

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

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);

    $arTemplateParameters['PROPERTY_SALARY'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_PROPERTY_SALARY'),
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y'
    );
}

$arTemplateParameters['SALARY_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_SALARY_SHOW'),
    'DEFAULT' => 'N'
);

$arTemplateParameters['FORM_SUMMARY_SHOW'] = array(
    'PARENT' => 'VISUAL',
    'TYPE' => 'CHECKBOX',
    'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_FORM_SUMMARY_SHOW'),
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['FORM_SUMMARY_SHOW'] == 'Y') {
    $arTemplateParameters['FORM_SUMMARY_ID'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_FORM_SUMMARY_ID'),
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    );

    if (!empty($arCurrentValues['FORM_SUMMARY_ID'])) {
        $arTemplateParameters['PROPERTY_FORM_SUMMARY_VACANCY'] = array(
            'PARENT' => 'DATA_SOURCE',
            'TYPE' => 'LIST',
            'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_PROPERTY_FORM_SUMMARY_VACANCY'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );
    }
}

$arTemplateParameters['CONSENT_URL'] = array(
    'PARENT' => 'URL_TEMPLATES',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_CONSENT_URL')
);

$arTemplateParameters['CURRENCY'] = array(
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING',
    'NAME' => GetMessage('C_NEWS_LIST_VACANCIES_2_CURRENCY')
);