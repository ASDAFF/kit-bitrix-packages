<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if ($arCurrentValues['ACTION'] === 'order') {
    $arForms = Arrays::fromDBResult(CForm::GetList(
        $by = 'sort',
        $order = 'asc',
        [],
        $filtered = false
    ))->indexBy('ID');

    $arForm = $arForms->get($arCurrentValues['FORM_ID']);
    $arTemplateParameters['FORM_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms->asArray(function ($iIndex, $arForm) {
            return [
                'key' => $arForm['ID'],
                'value' => '[' . $arForm['ID'] . '] ' . $arForm['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arForm)) {
        $arFormFields = array();
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

                $sId = 'form_' . $sType . '_' . $arFormAnswer['ID'];
                $arFormFields[$sId] = '[' . $arFormAnswer['ID'] . '] ' . $arFormField['TITLE'];
            }
        }

        $arTemplateParameters['FORM_PROPERTY_PRODUCT'] = array(
            'PARENT' => 'BASE',
            'TYPE' => 'LIST',
            'NAME' => Loc::getMessage('C_WIDGET_PRODUCTS_3_FORM_PROPERTY_PRODUCT'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );
    }
}