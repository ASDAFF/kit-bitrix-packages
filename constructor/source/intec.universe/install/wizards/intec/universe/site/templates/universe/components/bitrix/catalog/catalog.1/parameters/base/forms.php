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

$arForm = ArrayHelper::getValue($arCurrentValues, 'FORM_ID');
$arForm = $arForms->get($arForm);

$arTemplateParameters['FORM_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_FORM_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arForms->asArray(function ($iId, $arForm) {
        return [
            'key' => $arForm['ID'],
            'value' => '[' . $arForm['ID'] . '] ' . $arForm['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

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

    $arTemplateParameters['FORM_PROPERTY_PRODUCT'] = array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_FORM_PROPERTY_PRODUCT'),
        'VALUES' => $arFormFields,
        'ADDITIONAL_VALUES' => 'Y'
    );

    unset($arFormFields);
}

$arTemplateParameters['FORM_TEMPLATE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_FORM_TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arFormsTemplates,
    'ADDITIONAL_VALUES' => 'Y'
];

unset($arForm);
unset($arFormsTemplates);
unset($arForms);