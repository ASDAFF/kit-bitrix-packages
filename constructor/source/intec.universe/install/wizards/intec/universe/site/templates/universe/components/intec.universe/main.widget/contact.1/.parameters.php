<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var array $arCurrentValues
 */

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arCurrentValues = ArrayHelper::merge([
    'MAP_VENDOR' => 'yandex'
], $arCurrentValues);

$arForms = [];
$arFormsTemplates = [];

if ($arCurrentValues['FORM_SHOW'] == 'Y') {
    $rsFormsTemplates = [];

    if (Loader::includeModule('form')) {
        include(__DIR__.'/parameters/base/forms.php');
    } elseif (Loader::includeModule('intec.startshop')) {
        include(__DIR__.'/parameters/lite/forms.php');
    }

    if (!empty($rsFormsTemplates))
        foreach ($rsFormsTemplates as $arTemplate)
            $arFormsTemplates[$arTemplate['NAME']] = $arTemplate['NAME'] . (!empty($arTemplate['TEMPLATE']) ? ' (' . $arTemplate['TEMPLATE'] . ')' : null);

    unset($rsFormsTemplates);
}

$arTemplateParameters['MAP_VENDOR'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_MAP_VENDOR'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'google' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_MAP_VENDOR_GOOGLE'),
        'yandex' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_MAP_VENDOR_YANDEX'),
    ],
    'REFRESH' => 'Y',
    'DEFAULT' => 'yandex'
];


$arMapSettings = [ 'MAP_WIDTH', 'MAP_HEIGHT'];

if ($arCurrentValues['CONTACT_TYPE'] === 'IBLOCK')
    $arMapSettings[] = 'MAP_DATA';

if ($arCurrentValues['MAP_VENDOR'] === 'yandex') {
    $arTemplateParameters['MAP_INIT_MAP_TYPE'] = null;
    $arTemplateParameters['INIT_MAP_TYPE'] = null;

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        'bitrix:map.yandex.view',
        [
            '.default'
        ],
        $siteTemplate,
        $arCurrentValues,
        'MAP_',
        function ($sKey, $arParameter) use (&$arMapSettings) {
            if (in_array($sKey, $arMapSettings))
                return false;

            return true;
        },
        Component::PARAMETERS_MODE_BOTH
    ));

    $arTemplateParameters['INIT_MAP_TYPE'] = $arTemplateParameters['MAP_INIT_MAP_TYPE'];

    unset($arTemplateParameters['MAP_INIT_MAP_TYPE']);
} else if ($arCurrentValues['MAP_VENDOR'] === 'google') {
    $arTemplateParameters['MAP_INIT_MAP_TYPE'] = null;
    $arTemplateParameters['INIT_MAP_TYPE'] = null;

    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        'bitrix:map.google.view',
        [
            '.default'
        ],
        $siteTemplate,
        $arCurrentValues,
        'MAP_',
        function ($sKey, $arParameter) use (&$arMapSettings) {
            if (in_array($sKey, $arMapSettings))
                return false;

            return true;
        },
        Component::PARAMETERS_MODE_BOTH
    ));

    $arTemplateParameters['INIT_MAP_TYPE'] = $arTemplateParameters['MAP_INIT_MAP_TYPE'];

    unset($arTemplateParameters['MAP_INIT_MAP_TYPE']);
}

$arTemplateParameters['CONSENT_URL'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_CONSENT_URL'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['BLOCK_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BLOCK_SHOW'] == 'Y') {
    $arTemplateParameters['BLOCK_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_VIEW_LEFT'),
            'over' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_VIEW_OVER')
        ],
        'DEFAULT' => 'left'
    ];

    $arTemplateParameters['BLOCK_TITLE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_BLOCK_TITLE_DEFAULT')
    ];

    $arTemplateParameters['FORM_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FORM_SHOW'] == 'Y') {
        $arTemplateParameters['FORM_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arForms,
            'ADDITIONAL_VALUES' => 'Y'
        ];

        $arTemplateParameters['FORM_TEMPLATE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arFormsTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];

        $arTemplateParameters['FORM_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_TITLE_DEFAULT')
        ];

        $arTemplateParameters['FORM_BUTTON_TEXT'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_FORM_BUTTON_TEXT_DEFAULT')
        ];
    }

    $arTemplateParameters['ADDRESS_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_ADDRESS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PHONE_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PHONE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['EMAIL_SHOW'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_EMAIL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['CONTACT_TYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_CONTACT_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'PARAMS' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_CONTACT_TYPE_PARAMS'),
            'IBLOCK' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_CONTACT_TYPE_IBLOCK')
        ],
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['CONTACT_TYPE'] == 'IBLOCK') {

        $arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
        $arIBlocksFilter = [
            'ACTIVE' => 'Y'
        ];

        $sIBlockType = $arCurrentValues['IBLOCK_TYPE'];

        if (!empty($sIBlockType))
            $arIBlocksFilter['TYPE'] = $sIBlockType;

        $arIBlocks = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], $sIBlockType))->indexBy('ID');
        $arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

        $arTemplateParameters['IBLOCK_TYPE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        $arTemplateParameters['IBLOCK_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks->asArray(function ($iId, $arIBlock) {
                return [
                    'key' => $arIBlock['ID'],
                    'value' => '[' . $arIBlock['ID'] . '] ' . $arIBlock['NAME']
                ];
            }),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        /** DATA_SOURCE */
        if (!empty($arIBlock)) {
            $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arIBlock['ID']
            ]))->indexBy('ID');

            $hProperties = function ($sKey, $arProperty) {
                if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                    ];

                if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L')
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                    ];

                if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L')
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                    ];

                return ['skip' => true];
            };

            $hPropertiesMap = function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if ($arProperty['USER_TYPE'] === 'map_yandex' || $arProperty['USER_TYPE'] === 'map_google')
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                    ];

                return ['skip' => true];
            };

            $hPropertiesString = function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if ($arProperty['PROPERTY_TYPE'] !== 'S' || $arProperty['LIST_TYPE'] !== 'L')
                    return ['skip' => true];

                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];
            };

            $hPropertiesCheckbox = function ($sKey, $arProperty) {
                if (empty($arProperty['CODE']))
                    return ['skip' => true];

                if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C')
                    return ['skip' => true];

                return [
                    'key' => $arProperty['CODE'],
                    'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                ];
            };

            $hPropertiesMap = $arProperties->asArray($hPropertiesMap);
            $hPropertiesString = $arProperties->asArray($hPropertiesString);
            $hPropertiesCheckbox = $arProperties->asArray($hPropertiesCheckbox);

            $arElements = Arrays::fromDBResult(CIBlockelement::GetList([], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arIBlock['ID']
            ]))->indexBy('ID');

            $arTemplateParameters['MAIN'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_MAIN'),
                'TYPE' => 'LIST',
                'VALUES' => $arElements->asArray(function ($sKey, $arElement) {
                    return [
                        'key' => $arElement['ID'],
                        'value' => '[' . $arElement['ID'] . '] ' . $arElement['NAME']
                    ];
                }),
                'ADDITIONAL_VALUES' => 'Y'
            ];

            $arTemplateParameters['PROPERTY_MAP'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_MAP'),
                'TYPE' => 'LIST',
                'VALUES' => $hPropertiesMap,
                'ADDITIONAL_VALUES' => 'Y'
            ];

            if ($arCurrentValues['ADDRESS_SHOW'] == 'Y') {
                $arTemplateParameters['PROPERTY_CITY'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_CITY'),
                    'TYPE' => 'LIST',
                    'VALUES' => $hPropertiesString,
                    'ADDITIONAL_VALUES' => 'Y'
                ];
                $arTemplateParameters['PROPERTY_STREET'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_STREET'),
                    'TYPE' => 'LIST',
                    'VALUES' => $hPropertiesString,
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }

            if ($arCurrentValues['PHONE_SHOW'] == 'Y') {
                $arTemplateParameters['PROPERTY_PHONE'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_PHONE'),
                    'TYPE' => 'LIST',
                    'VALUES' => $hPropertiesString,
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }

            if ($arCurrentValues['EMAIL_SHOW'] == 'Y') {
                $arTemplateParameters['PROPERTY_EMAIL'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_EMAIL'),
                    'TYPE' => 'LIST',
                    'VALUES' => $hPropertiesString,
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }

            include(__DIR__ . '/parameters/regioinality.php');

            if ($arCurrentValues['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')) {
                $arTemplateParameters['REGIONALITY_STRICT'] = [
                    'PARENT' => 'BASE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_REGIONALITY_STRICT'),
                    'TYPE' => 'CHECKBOX'
                ];

                $arTemplateParameters['PROPERTY_REGION'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PROPERTY_REGION'),
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
                            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
                        ];
                    }),
                    'ADDITIONAL_VALUES' => 'Y'
                ];
            }
        }
    } else {
        if ($arCurrentValues['ADDRESS_SHOW'] == 'Y') {
            $arTemplateParameters['ADDRESS_CITY'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_ADDRESS_CITY'),
                'TYPE' => 'STRING'
            ];

            $arTemplateParameters['ADDRESS_STREET'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_ADDRESS_STREET'),
                'TYPE' => 'STRING'
            ];
        }

        if ($arCurrentValues['PHONE_SHOW'] == 'Y') {
            $arTemplateParameters['PHONE_VALUES'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_PHONE_VALUES'),
                'TYPE' => 'STRING',
                'MULTIPLE' => 'Y'
            ];
        }

        if ($arCurrentValues['EMAIL_SHOW'] == 'Y') {
            $arTemplateParameters['EMAIL_VALUES'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_WIDGET_CONTACT_1_EMAIL_VALUES'),
                'TYPE' => 'STRING',
                'MULTIPLE' => 'Y'
            ];
        }
    }
}