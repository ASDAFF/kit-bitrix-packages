<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if ($arCurrentValues['ACTION'] === 'order') {
    $arForms = Arrays::fromDBResult(CStartShopForm::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $arForm = $arForms->get($arCurrentValues['FORM_ID']);
    $arTemplateParameters['FORM_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms->asArray(function ($iIndex, $arForm) {
            return [
                'key' => $arForm['ID'],
                'value' => '['.$arForm['ID'].'] '.$arForm['LANG'][LANGUAGE_ID]['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arForm)) {
        $arFormFields = array();
        $rsFormFields = CStartShopFormProperty::GetList([
            'SORT' => 'ASC'
        ], [
            'FORM' => $arForm['ID'],
            'ACTIVE' => 'Y'
        ]);

        while ($arFormField = $rsFormFields->GetNext())
            $arFormFields[$arFormField['CODE']] = '['.$arFormField['CODE'].'] '.$arFormField['LANG'][LANGUAGE_ID]['NAME'];

        $arTemplateParameters['FORM_PROPERTY_PRODUCT'] = array(
            'PARENT' => 'BASE',
            'TYPE' => 'LIST',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_FORM_PROPERTY_PRODUCT'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );
    }
}