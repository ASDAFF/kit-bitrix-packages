<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        3 => 3,
        4 => 4,
        5 => 5,
    ],
    'DEFAULT' => 4
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arIBlock = CIBlock::GetByID($arCurrentValues['IBLOCK_ID']);
    $arIBlock = $arIBlock->GetNext();

    if (!empty($arIBlock) && $arIBlock['ACTIVE'] === 'Y') {
        $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
            'IBLOCK_ID' => $arIBlock['ID'],
            'ACTIVE' => 'Y'
        ]));

        $fPropertiesText = function ($iIndex, $arProperty) {
            if (empty($arProperty['CODE']))
                return ['skip' => true];

            if (
                $arProperty['PROPERTY_TYPE'] !== 'S' ||
                !empty($arProperty['USER_TYPE']) ||
                $arProperty['MULTIPLE'] === 'Y'
            ) return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        };

        $arTemplateParameters['POSITION_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_POSITION_SHOW'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['POSITION_SHOW'] === 'Y') {
            $arTemplateParameters['PROPERTY_POSITION'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_PROPERTY_POSITION'),
                'TYPE' => 'LIST',
                'ADDITIONAL_VALUES' => 'Y',
                'VALUES' => $arProperties->asArray($fPropertiesText),
                'REFRESH' => 'Y'
            ];
        }

        $arTemplateParameters['SOCIALS_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_SOCIALS_SHOW'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['SOCIALS_SHOW'] === 'Y') {
            $arTemplateParameters['PROPERTY_LINK_VKONTAKTE'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_PROPERTY_LINK_VKONTAKTE'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($fPropertiesText),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['PROPERTY_LINK_FACEBOOK'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_PROPERTY_LINK_FACEBOOK'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($fPropertiesText),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['PROPERTY_LINK_INSTAGRAM'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_PROPERTY_LINK_INSTAGRAM'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($fPropertiesText),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['PROPERTY_LINK_TWITTER'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_STAFF_TEMPLATE_1_PROPERTY_LINK_TWITTER'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($fPropertiesText),
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }
}