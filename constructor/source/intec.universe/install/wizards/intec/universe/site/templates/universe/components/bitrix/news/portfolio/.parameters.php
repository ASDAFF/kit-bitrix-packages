<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arIBlockTypes = CIBlockParameters::GetIBlockTypes();

    $arIBlocks = Arrays::fromDBResult(CIBlock::GetList([], []))->indexBy('ID');

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyAll = function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['CODE'],
            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
        ];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
    $arPropertyAll = $arProperties->asArray($hPropertyAll);
}

$arTemplateParameters = [];

/** LIST_SETTINGS */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['LIST_PROPERTY_TYPE'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_PROPERTY_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['LIST_WIDE'] = [
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];
$arTemplateParameters['LIST_COLUMNS'] = [
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => ArrayHelper::merge([
        3 => '3',
        4 => '4'
    ], $arCurrentValues['LIST_WIDE'] === 'Y' ? [5 => '5'] : []),
    'DEFAULT' => 4
];

if (!empty($arCurrentValues['LIST_PROPERTY_TYPE'])) {
    $arTemplateParameters['LIST_TABS_USE'] = [
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_TABS_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['LIST_TABS_USE'] === 'Y') {
        $arTemplateParameters['LIST_TABS_POSITION'] = [
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_TABS_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_POSITION_LEFT'),
                'center' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_POSITION_CENTER'),
                'right' => Loc::getMessage('C_NEWS_PORTFOLIO_LIST_POSITION_RIGHT')
            ],
            'DEFAULT' => 'center'
        ];
    }
}

/** DETAIL_SETTINGS */
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['DETAIL_PROPERTY_BANNER_TYPE'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_BANNER_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_BANNER_URL'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_BANNER_URL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_BANNER_IMAGE'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_BANNER_IMAGE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_CHARACTERISTICS'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_CHARACTERISTICS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyAll,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_INFORMATION'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_INFORMATION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyAll,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_FEATURES'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_FEATURES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_EXAMPLE'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_EXAMPLE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_RESULT'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_RESULT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_REVIEW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_REVIEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['DETAIL_PROPERTY_SERVICES'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_SERVICES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['DETAIL_PROPERTY_SERVICES'])) {
        if (!empty($arCurrentValues['DETAIL_SERVICES_IBLOCK_TYPE']))
            $arIBlocksServices = $arIBlocks->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
                if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['DETAIL_SERVICES_IBLOCK_TYPE']) {
                    return [
                        'key' => $arProperty['ID'],
                        'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
                    ];
                }

                return ['skip' => true];
            });
        else
            $arIBlocksServices = $arIBlocks->asArray(function ($sKey, $arProperty) {
                return [
                    'key' => $arProperty['ID'],
                    'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
                ];
            });

        $arTemplateParameters['DETAIL_SERVICES_IBLOCK_TYPE'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_SERVICES_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['DETAIL_SERVICES_IBLOCK_ID'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_SERVICES_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocksServices,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['DETAIL_SERVICES_IBLOCK_ID'])) {
            $arPropertiesServices = Arrays::fromDBResult(CIBlockProperty::GetList([], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arCurrentValues['DETAIL_SERVICES_IBLOCK_ID']
            ]))->indexBy('ID');

            $arTemplateParameters['DETAIL_SERVICES_PROPERTY_LINK'] = [
                'PARENT' => 'DETAIL_SETTINGS',
                'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_SERVICES_PROPERTY_LINK'),
                'TYPE' => 'LIST',
                'VALUES' => $arPropertiesServices->asArray(function ($sKey, $arProperty) {
                    if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
                        return [
                            'key' => $arProperty['CODE'],
                            'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                        ];

                    return ['skip' => true];
                }),
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }

    $arTemplateParameters['DETAIL_PROPERTY_PROJECTS'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_PROJECTS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyLink,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['DETAIL_BANNER_SHOW'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DETAIL_BANNER_SHOW']) {
    $arTemplateParameters['DETAIL_BANNER_HEIGHT'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_HEIGHT'),
        'TYPE' => 'LIST',
        'VALUES' => [
            500 => '500px',
            550 => '550px',
            600 => '600px',
            650 => '650px',
            700 => '700px',
            750 => '750px',
            800 => '800px'
        ],
        'ADDITIONAL_VALUES' => 'Y',
        'DEFAULT' => 650
    ];

    if (!empty($arCurrentValues['DETAIL_PROPERTY_BANNER_TYPE'])) {
        $arTemplateParameters['DETAIL_BANNER_TYPE'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_TYPE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    if (!empty($arCurrentValues['DETAIL_PROPERTY_BANNER_URL'])) {
        $arTemplateParameters['DETAIL_BANNER_URL'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_URL'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];

        $arTemplateParameters['DETAIL_BANNER_BUTTON'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_BUTTON_DEFAULT')
        ];
    }

    if (!empty($arCurrentValues['DETAIL_PROPERTY_BANNER_IMAGE'])) {
        $arTemplateParameters['DETAIL_BANNER_IMAGE'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_BANNER_IMAGE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['DETAIL_CHARACTERISTICS_SHOW'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_CHARACTERISTICS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DETAIL_CHARACTERISTICS_SHOW'] === 'Y') {
    $arTemplateParameters['DETAIL_CHARACTERISTICS_COLUMNS'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_CHARACTERISTICS_COLUMNS'),
        'TYPE' => 'LIST',
        'VALUES' => [
            3 => '3',
            4 => '4',
            5 => '5',
        ],
        'DEFAULT' => 4
    ];
}

$arTemplateParameters['DETAIL_INFORMATION_SHOW'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_INFORMATION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DETAIL_INFORMATION_SHOW'] === 'Y') {
    $arTemplateParameters['DETAIL_INFORMATION_COLUMNS'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_INFORMATION_COLUMNS'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => '1',
            2 => '2',
            3 => '3',
        ],
        'DEFAULT' => 2
    ];
}

if (!empty($arCurrentValues['DETAIL_PROPERTY_FEATURES'])) {
    $arTemplateParameters['DETAIL_FEATURES_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_FEATURES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['DETAIL_FEATURES_SHOW'] === 'Y') {
        $arTemplateParameters['DETAIL_FEATURES_NARROW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_FEATURES_NARROW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['DETAIL_EXAMPLE_SHOW'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_EXAMPLE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DETAIL_EXAMPLE_SHOW'] === 'Y') {
    $arTemplateParameters['DETAIL_EXAMPLE_SHADOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_EXAMPLE_SHADOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['DETAIL_PROPERTY_RESULT'])) {
    $arTemplateParameters['DETAIL_RESULT_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_RESULT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['DETAIL_RESULT_SHOW'] === 'Y') {
        $arTemplateParameters['DETAIL_RESULT_NARROW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_RESULT_NARROW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['DETAIL_RESULT_BACKGROUND'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_RESULT_BACKGROUND'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['DETAIL_PROPERTY_REVIEW'])) {
    $arTemplateParameters['DETAIL_REVIEW_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_REVIEW_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['DETAIL_REVIEW_SHOW'] === 'Y') {
        $arTemplateParameters['DETAIL_REVIEW_NARROW'] = [
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_REVIEW_NARROW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['DETAIL_PROPERTY_SERVICES']) && !empty($arCurrentValues['DETAIL_SERVICES_IBLOCK_ID'])) {
    $arTemplateParameters['DETAIL_SERVICES_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_SERVICES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['DETAIL_PROPERTY_PROJECTS'])) {
    $arTemplateParameters['DETAIL_PROJECTS_SHOW'] = [
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('C_NEWS_PORTFOLIO_DETAIL_PROPERTY_PROJECTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}