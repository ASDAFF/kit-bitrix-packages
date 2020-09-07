<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var InnerTemplate $template
 */

$arTemplateParameters['CONTACTS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

$arTemplateParameters['ADDRESS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_ADDRESS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ADDRESS_SHOW'] === 'Y') {
    $arTemplateParameters['ADDRESS_VALUE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_ADDRESS_VALUE'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['EMAIL_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_EMAIL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['EMAIL_SHOW'] === 'Y') {
    $arTemplateParameters['EMAIL_VALUE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_EMAIL_VALUE'),
        'TYPE' => 'STRING'
    ];
}

if ($arCurrentValues['CONTACTS_USE'] === 'Y') {
    if ($arCurrentValues['PHONE_SHOW'] === 'Y')
        $arTemplateParameters['PHONE_VALUE'] = ['HIDDEN' => 'Y'];

    unset($arTemplateParameters['ADDRESS_VALUE']);
    unset($arTemplateParameters['EMAIL_VALUE']);

    if (Loader::includeModule('iblock')) {
        $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
        $arIBlock = null;
        $arProperties = Arrays::from([]);
        $arElements = Arrays::from([]);
        $arFilter = [
            'ACTIVE' => 'Y'
        ];

        if (!empty($arCurrentValues['CONTACTS_IBLOCK_TYPE']))
            $arFilter['TYPE'] = $arCurrentValues['CONTACTS_IBLOCK_TYPE'];

        $arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
            'SORT' => 'ASC'
        ], $arFilter))->indexBy('ID');

        if (!empty($arCurrentValues['CONTACTS_IBLOCK_ID']))
            $arIBlock = $arIBlocks->get($arCurrentValues['CONTACTS_IBLOCK_ID']);

        $arTemplateParameters['CONTACTS_IBLOCK_TYPE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_IBLOCK_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) {
                return [
                    'key' => $arIBlock['ID'],
                    'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arIBlock)) {
            $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
                'SORT' => 'ASC'
            ], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arIBlock['ID']
            ]))->indexBy('ID');

            $arElements = Arrays::fromDBResult(CIBlockElement::GetList([
                'SORT' => 'ASC'
            ], [
                'ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y',
                'IBLOCK_ID' => $arIBlock['ID']
            ]))->indexBy('ID');
        }

        $arPropertiesString = $arProperties->asArray(function ($iId, $arProperty) {
            if (
                empty($arProperty['CODE']) ||
                $arProperty['PROPERTY_TYPE'] !== 'S' ||
                $arProperty['USER_TYPE'] !== null
            ) return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        });

        $arTemplateParameters['CONTACTS_ELEMENTS'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_ELEMENTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arElements->asArray(function ($iId, $arElement) {
                return [
                    'key' => $arElement['ID'],
                    'value' => '['.$arElement['ID'].'] '.$arElement['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_ELEMENT'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_ELEMENT'),
            'TYPE' => 'LIST',
            'VALUES' => $arElements->asArray(function ($iId, $arElement) use (&$arCurrentValues) {
                $arElements = $arCurrentValues['CONTACTS_ELEMENTS'];

                if (!ArrayHelper::isEmpty($arElements, true))
                    if (!ArrayHelper::isIn($arElement['ID'], $arElements))
                        return ['skip' => true];

                return [
                    'key' => $arElement['ID'],
                    'value' => '['.$arElement['ID'].'] '.$arElement['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_PROPERTY_CITY'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_PROPERTY_CITY'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_PROPERTY_ADDRESS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_PROPERTY_ADDRESS'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_PROPERTY_PHONE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_PROPERTY_PHONE'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['CONTACTS_PROPERTY_EMAIL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_PROPERTY_EMAIL'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesString,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        if ($arCurrentValues['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')) {
            $arTemplateParameters['CONTACTS_REGIONALITY_USE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_REGIONALITY_USE'),
                'TYPE' => 'CHECKBOX',
                'REFRESH' => 'Y'
            ];

            if ($arCurrentValues['CONTACTS_REGIONALITY_USE'] === 'Y') {
                $arTemplateParameters['CONTACTS_REGIONALITY_STRICT'] = [
                    'PARENT' => 'BASE',
                    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_REGIONALITY_STRICT'),
                    'TYPE' => 'CHECKBOX'
                ];

                $arTemplateParameters['CONTACTS_PROPERTY_REGION'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_FOOTER_TEMPLATE_1_CONTACTS_PROPERTY_REGION'),
                    'TYPE' => 'LIST',
                    'VALUES' => $arProperties->asArray(function ($iId, $arProperty) {
                        if (empty($arProperty['CODE']))
                            return ['skip' => true];

                        if (
                            $arProperty['PROPERTY_TYPE'] !== RegionProperty::PROPERTY_TYPE ||
                            $arProperty['USER_TYPE'] !== RegionProperty::USER_TYPE
                        ) return ['skip' => true];

                        return [
                            'key' => $arProperty['CODE'],
                            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                        ];
                    }),
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }
        }
    }
}