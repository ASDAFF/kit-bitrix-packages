<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arIBlockTypes = CIBlockParameters::GetIBlockTypes();
    $arIBlocksList = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'SITE_ID' => $sSite
    ]))->indexBy('ID');

    if (!empty($arCurrentValues['BLOCKS_IBLOCK_TYPE']))
        $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['BLOCKS_IBLOCK_TYPE'])
                return [
                    'key' => $arProperty['ID'],
                    'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) {
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
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
    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'F' && $arProperty['LIST_TYPE'] == 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyList = $arProperties->asArray($hPropertyList);

    if ($arCurrentValues['BLOCKS_USE'] === 'Y' && !empty($arCurrentValues['BLOCKS_IBLOCK_ID'])) {
        $arBlocksSections = Arrays::fromDBResult(CIBlockSection::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['BLOCKS_IBLOCK_ID'],
            'GLOBAL_ACTIVE' => 'Y'
        ]))->indexBy('ID');

        if ($arCurrentValues['BLOCKS_MODE'] === 'Y')
            $arBlocksSections = $arBlocksSections->asArray(function ($sKey, $arSection) {
                return [
                    'key' => $arSection['CODE'],
                    'value' => '['.$arSection['CODE'].'] '.$arSection['NAME']
                ];
            });
        else
            $arBlocksSections = $arBlocksSections->asArray(function ($sKey, $arSection) {
                return [
                    'key' => $arSection['ID'],
                    'value' => '['.$arSection['ID'].'] '.$arSection['NAME']
                ];
            });

        $arBlocksProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['BLOCKS_IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]))->indexBy('ID');

        $arBlocksPropertyText = $arBlocksProperties->asArray($hPropertyText);
        $arBlocksPropertyCheckbox = $arBlocksProperties->asArray($hPropertyCheckbox);
    }
}

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['BLOCKS_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BLOCKS_USE'] === 'Y') {
        $arTemplateParameters['BLOCKS_IBLOCK_TYPE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockTypes,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_IBLOCK_ID'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['BLOCKS_IBLOCK_ID'])) {
            $arTemplateParameters['BLOCKS_MODE'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_MODE'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y'
            ];
            $arTemplateParameters['BLOCKS_SECTIONS'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_SECTIONS'),
                'TYPE' => 'LIST',
                'VALUES' => $arBlocksSections,
                'MULTIPLE' => 'Y',
                'ADDITIONAL_VALUES' => 'Y'
            ];
            $arTemplateParameters['BLOCKS_ELEMENTS_COUNT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_ELEMENTS_COUNT'),
                'TYPE' => 'LIST',
                'VALUES' => $arCurrentValues['BLOCKS_POSITION'] === 'right' ? [
                    1 => '1',
                    2 => '2',
                    3 => '3'
                ] : [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4'
                ],
                'DEFAULT' => 2,
                'REFRESH' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_HEADER_OVER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_HEADER_OVER'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_LINK'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_LINK'])) {
        $arTemplateParameters['PROPERTY_LINK_BLANK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_LINK_BLANK'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['PROPERTY_BUTTON_SHOW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_BUTTON_SHOW'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
            $arTemplateParameters['PROPERTY_BUTTON_TEXT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_BUTTON_TEXT'),
                'TYPE' => 'LIST',
                'VALUES' => $arPropertyText,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_PICTURE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_PICTURE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_PICTURE'])) {
        $arTemplateParameters['PROPERTY_PICTURE_ALIGN_VERTICAL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_PICTURE_ALIGN_VERTICAL'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyList,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_TEXT_HALF'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_TEXT_HALF'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_FADE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_FADE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_SCHEME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_SCHEME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_FADE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_FADE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_MP4'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_VIDEO_FILE_MP4'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_WEBM'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_VIDEO_FILE_WEBM'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_OGV'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PROPERTY_VIDEO_FILE_OGV'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BLOCKS_USE'] === 'Y' && !empty($arCurrentValues['BLOCKS_IBLOCK_ID'])) {
        $arTemplateParameters['BLOCKS_PROPERTY_LINK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_PROPERTY_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $arBlocksPropertyText,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['BLOCKS_PROPERTY_LINK'])) {
            $arTemplateParameters['BLOCKS_PROPERTY_LINK_BLANK'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_PROPERTY_LINK_BLANK'),
                'TYPE' => 'LIST',
                'VALUES' => $arBlocksPropertyCheckbox,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }
}

$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_HEIGHT'),
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
    'DEFAULT' => 600
];
$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_HEADER'])) {
    $arTemplateParameters['HEADER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_HEADER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
        $arTemplateParameters['HEADER_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_HEADER_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1'),
                2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_2'),
                3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_3'),
                4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_4'),
                5 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_5')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_DESCRIPTION'])) {
    $arTemplateParameters['DESCRIPTION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
        $arTemplateParameters['DESCRIPTION_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_DESCRIPTION_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1'),
                2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_2'),
                3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_3'),
                4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_4'),
                5 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_5')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_HEADER_OVER'])) {
    $arTemplateParameters['HEADER_OVER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_HEADER_OVER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['HEADER_OVER_SHOW'] === 'Y') {
        $arTemplateParameters['HEADER_OVER_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_HEADER_OVER_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
    $arTemplateParameters['BUTTON_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BUTTON_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1'),
            2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_2'),
            3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_3'),
            4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_4')
        ],
        'DEFAULT' => 1
    ];
}

if (!empty($arCurrentValues['PROPERTY_PICTURE'])) {
    $arTemplateParameters['PICTURE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_PICTURE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (
    !empty($arCurrentValues['PROPERTY_VIDEO']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_MP4']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_WEBM']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_OGV'])
) {
    $arTemplateParameters['VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if ($arCurrentValues['BLOCKS_USE'] === 'Y' && !empty($arCurrentValues['BLOCKS_IBLOCK_ID'])) {
    $arTemplateParameters['BLOCKS_EFFECT_FADE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_EFFECT_FADE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['BLOCKS_EFFECT_SCALE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_EFFECT_SCALE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['BLOCKS_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'right' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_POSITION_RIGHT'),
            'bottom' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_POSITION_BOTTOM')
        ],
        'DEFAULT' => 'right',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BLOCKS_POSITION'] == 'bottom') {
        $arTemplateParameters['BLOCKS_HEIGHT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_BLOCKS_HEIGHT'),
            'TYPE' => 'LIST',
            'VALUES' => [
                200 => '200px',
                250 => '250px',
                300 => '300px'
            ],
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => 300
        ];
    }
}

$arTemplateParameters['SLIDER_NAV_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_NAV_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_NAV_SHOW'] === 'Y') {
    $arTemplateParameters['SLIDER_NAV_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_NAV_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1')
        ],
        'DEFAULT' => 1
    ];
}

$arTemplateParameters['SLIDER_DOTS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_DOTS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_DOTS_SHOW'] === 'Y') {
    $arTemplateParameters['SLIDER_DOTS_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_DOTS_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_1'),
            2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_VIEW_2')
        ],
        'DEFAULT' => 1
    ];
}

$arTemplateParameters['SLIDER_LOOP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_LOOP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['SLIDER_AUTO_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_AUTO_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_AUTO_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_AUTO_TIME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_AUTO_TIME'),
        'TYPE' => 'STRING',
        'DEFAULT' => '10000'
    ];
    $arTemplateParameters['SLIDER_AUTO_HOVER'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_3_SLIDER_AUTO_HOVER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}