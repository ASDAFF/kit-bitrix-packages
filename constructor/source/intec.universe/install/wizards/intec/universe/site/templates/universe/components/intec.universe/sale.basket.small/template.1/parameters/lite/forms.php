<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

if ($arCurrentValues['FORM_SHOW'] === 'Y') {
    $arForms = Arrays::fromDBResult(CStartShopForm::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $arTemplateParameters['FORM_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_FORM_ID'),
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
}