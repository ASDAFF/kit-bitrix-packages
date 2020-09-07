<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var InnerTemplate $template
 */

$rsTemplates = null;
$arForms = [];
$arTemplates = [];

if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y') {
    if (Loader::includeModule('form')) {
        $rsForms = CForm::GetList(
            $by = 'sort',
            $order = 'asc',
            [],
            $filtered = false
        );

        while ($arForm = $rsForms->Fetch())
            $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.$arForm['NAME'];

        unset($rsForms);

        $rsTemplates = CComponentUtil::GetTemplatesList('bitrix:form.result.new', $siteTemplate);
    } else if (Loader::includeModule('intec.startshop')) {
        $rsForms = CStartShopForm::GetList();

        while ($arForm = $rsForms->Fetch())
            $arForms[$arForm['ID']] = '['.$arForm['ID'].'] '.(!empty($arForm['LANG'][LANGUAGE_ID]['NAME']) ? $arForm['LANG'][LANGUAGE_ID]['NAME'] : $arForm['CODE']);

        unset($rsForms);

        $rsTemplates = CComponentUtil::GetTemplatesList('intec:startshop.forms.result.new', $siteTemplate);
    } else {
        return;
    }

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

$arTemplateParameters['FORMS_CALL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FORMS_CALL_SHOW'] == 'Y') {
    $arTemplateParameters['FORMS_CALL_ID'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['FORMS_CALL_TEMPLATE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arTemplates,
        'DEFAULT' => '.default',
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arTemplateParameters['FORMS_CALL_TITLE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_FOOTER_TEMPLATE_1_FORMS_CALL_TITLE_DEFAULT')
    ];

    $arTemplateParameters['CONSENT_URL'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONSENT_URL'),
        'TYPE' => 'STRING'
    ];
}