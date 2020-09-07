<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

$arForms = Arrays::fromDBResult(CForm::GetList(
    $sBy = 'sort',
    $sOrder = 'asc',
    [],
    $bIsFiltered
))->indexBy('ID');

$rsFormsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new');
$arFormsTemplates = [];

foreach ($rsFormsTemplates as $arFormsTemplate)
    $arFormsTemplates[$arFormsTemplate['NAME']] = $arFormsTemplate['NAME'].(!empty($arFormsTemplate['TEMPLATE']) ? ' ('.$arFormsTemplate['TEMPLATE'].')' : null);

unset($arFormsTemplate);
unset($rsFormsTemplates);

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
        'VALUES' => $arForms->asArray(function ($iId, $arForm) {
            return [
                'key' => $arForm['ID'],
                'value' => '[' . $arForm['ID'] . '] ' . $arForm['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['FORMS_CHEAPER_TEMPLATE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arFormsTemplates,
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arForm = ArrayHelper::getValue($arCurrentValues, 'FORMS_CHEAPER_ID');
$arForm = $arForms->get($arForm);

if (!empty($arForm)) {
    $arFormFields = [];
    $rsFormFields = CFormField::GetList(
        $arForm['ID'],
        'N',
        $by = null,
        $asc = null,
        [
            'ACTIVE' => 'Y'
        ],
        $filtered = false
    );

    while ($arFormField = $rsFormFields->GetNext()) {
        $rsFormAnswers = CFormAnswer::GetList(
            $arFormField['ID'],
            $sort = '',
            $order = '',
            [],
            $filtered = false
        );

        while ($arFormAnswer = $rsFormAnswers->GetNext()) {
            $sType = $arFormAnswer['FIELD_TYPE'];

            if (empty($sType))
                continue;

            $sId = 'form_'.$sType.'_'.$arFormAnswer['ID'];
            $arFormFields[$sId] = '['.$arFormAnswer['ID'].'] '.$arFormField['TITLE'];
        }
    }

    unset($arFormField);

    $arTemplateParameters['FORMS_CHEAPER_PROPERTY_PRODUCT'] = array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORMS_CHEAPER_PROPERTY_PRODUCT'),
        'VALUES' => $arFormFields,
        'ADDITIONAL_VALUES' => 'Y'
    );

    unset($arFormFields);
}

unset($arForm);
unset($arFormsTemplates);
unset($arForms);