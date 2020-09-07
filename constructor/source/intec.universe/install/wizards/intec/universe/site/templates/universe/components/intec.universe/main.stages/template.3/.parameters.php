<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

$arTemplateParameters = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
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

    $arTemplateParameters['PROPERTY_TIME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_PROPERTY_TIME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
    ];
    $arTemplateParameters['PROPERTY_TEXT_SOURCE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_PROPERTY_TEXT_SOURCE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'preview' => Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_PROPERTY_TEXT_SOURCE_PREVIEW'),
            'detail' => Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_PROPERTY_TEXT_SOURCE_DETAIL')
        ],
        'DEFAULT' => 'preview'
    ];
}

$arTemplateParameters['ELEMENT_NAME_SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_ELEMENT_NAME_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'big' => '45px',
        'normal' => '40px'
    ],
    'DEFAULT' => 'big'
];
