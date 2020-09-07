<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var array $arParts
 * @var InnerTemplate $desktopTemplate
 * @var InnerTemplate $fixedTemplate
 * @var InnerTemplate $mobileTemplate
 */

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($fixedTemplate)) {
    $arTemplateParameters['PHONES_SHOW_FIXED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_PHONES_SHOW_FIXED'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

if (!empty($mobileTemplate)) {
    $arTemplateParameters['PHONES_SHOW_MOBILE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_PHONES_SHOW_MOBILE'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['PHONES_ADVANCED_MODE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_PHONES_ADVANCED_MODE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PHONES_ADVANCED_MODE'] == 'Y') {
    $arTemplateParameters['CONTACTS_ADDRESS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_ADDRESS_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];

    $arTemplateParameters['CONTACTS_SCHEDULE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_SCHEDULE_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];

    $arTemplateParameters['CONTACTS_EMAIL_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_EMAIL_SHOW'),
        'TYPE' => 'CHECKBOX'
    ];

    if ($arCurrentValues['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')) {
        $arTemplateParameters['CONTACTS_REGIONALITY_USE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_REGIONALITY_USE'),
            'TYPE' => 'CHECKBOX',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['CONTACTS_REGIONALITY_USE'] === 'Y')
            $arTemplateParameters['CONTACTS_REGIONALITY_STRICT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_REGIONALITY_STRICT'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'Y'
            ];
    }

    if (Loader::includeModule('iblock')) {
        $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();

        $arTemplateParameters['CONTACTS_IBLOCK_TYPE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arIBlocks = [];
        $rsIBlocks = CIBlock::GetList([], [
            'ACTIVE' => 'Y',
            'TYPE' => $arCurrentValues['CONTACTS_IBLOCK_TYPE']
        ]);

        while ($arIBlock = $rsIBlocks->GetNext())
            $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

        $arTemplateParameters['CONTACTS_IBLOCK_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['CONTACTS_IBLOCK_ID'])) {
            $arItems = Arrays::fromDBResult(CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'ACTIVE_DATE' => 'Y',
                    'IBLOCK_ID' => $arCurrentValues['CONTACTS_IBLOCK_ID']
                ]
            ))->indexBy('ID');

            $arTemplateParameters['CONTACTS_ELEMENTS'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_ELEMENTS'),
                'TYPE' => 'LIST',
                'MULTIPLE' => 'Y',
                'VALUES' => $arItems->asArray(function ($sKey, $arItem) {
                    return [
                        'key' => $arItem['ID'],
                        'value' => '['.$arItem['ID'].'] '.$arItem['NAME']
                    ];
                }),
                'ADDITIONAL_VALUES' => 'Y',
                'REFRESH' => 'Y'
            ];

            $arTemplateParameters['CONTACTS_ELEMENT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_ELEMENT'),
                'TYPE' => 'LIST',
                'VALUES' => $arItems->asArray(function ($sKey, $arItem) use (&$arCurrentValues) {
                    $arItems = $arCurrentValues['CONTACTS_ELEMENTS'];

                    if (!ArrayHelper::isEmpty($arItems, true))
                        if (!ArrayHelper::isIn($arItem['ID'], $arItems))
                            return ['skip' => true];

                    return [
                        'key' => $arItem['ID'],
                        'value' => '['.$arItem['ID'].'] '.$arItem['NAME']
                    ];
                }),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arCurrentValues['CONTACTS_IBLOCK_ID']
                ]
            ))->indexBy('ID');

            $hContactProperties = function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if ($arProperty['PROPERTY_TYPE'] == 'S' || $arProperty['PROPERTY_TYPE'] == 'L')
                    return [
                        'skip' => false,
                        'key' => $arProperty['CODE'],
                        'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                    ];

                return ['skip' => true];
            };

            $hRegionProperties = function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if ($arProperty['PROPERTY_TYPE'] == RegionProperty::PROPERTY_TYPE && $arProperty['USER_TYPE'] == RegionProperty::USER_TYPE)
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                    ];

                return ['skip' => true];
            };

            $arTemplateParameters['CONTACTS_PROPERTY_PHONE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_PHONE'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($hContactProperties),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['CONTACTS_PROPERTY_CITY'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_CITY'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($hContactProperties),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['CONTACTS_PROPERTY_ADDRESS'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_ADDRESS'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($hContactProperties),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['CONTACTS_PROPERTY_SCHEDULE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_SCHEDULE'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($hContactProperties),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['CONTACTS_PROPERTY_EMAIL'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_EMAIL'),
                'TYPE' => 'LIST',
                'VALUES' => $arProperties->asArray($hContactProperties),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            if ($arCurrentValues['CONTACTS_REGIONALITY_USE'] === 'Y' && $arCurrentValues['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')) {
                $arTemplateParameters['CONTACTS_PROPERTY_REGION'] = [
                    'PARENT' => 'BASE',
                    'NAME' => Loc::getMessage('C_HEADER_TEMP1_CONTACTS_PROPERTY_REGION'),
                    'TYPE' => 'LIST',
                    'VALUES' => $arProperties->asArray($hRegionProperties),
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }
        }
    }
}
