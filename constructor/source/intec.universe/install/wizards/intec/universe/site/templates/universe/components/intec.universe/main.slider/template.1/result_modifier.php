<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_HEADER_OVER' => null,
    'PROPERTY_LINK' => null,
    'PROPERTY_LINK_BLANK' => null,
    'PROPERTY_BUTTON_SHOW' => null,
    'PROPERTY_BUTTON_TEXT' => null,
    'PROPERTY_TEXT_POSITION' => null,
    'PROPERTY_TEXT_HALF' => null,
    'PROPERTY_PICTURE' => null,
    'PROPERTY_PICTURE_VERTICAL' => null,
    'PROPERTY_ADDITIONAL' => null,
    'PROPERTY_SCHEME' => null,
    'PROPERTY_FADE' => null,
    'PROPERTY_VIDEO' => null,
    'PROPERTY_VIDEO_FILE_MP4' => null,
    'PROPERTY_VIDEO_FILE_WEBM' => null,
    'PROPERTY_VIDEO_FILE_OGV' => null,
    'HEIGHT' => 600,
    'HEADER_SHOW' => 'N',
    'HEADER_H1' => 'N',
    'HEADER_VIEW' => 1,
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_VIEW' => 1,
    'HEADER_OVER_SHOW' => 'N',
    'HEADER_OVER_VIEW' => 1,
    'BUTTON_VIEW' => 1,
    'PICTURE_SHOW' => 'N',
    'VIDEO_SHOW' => 'N',
    'ADDITIONAL_SHOW' => 'N',
    'ADDITIONAL_VIEW' => 1,
    'BUTTONS_BACK_SHOW' => 'N',
    'BUTTONS_BACK_LINK' => null,
    'SLIDER_NAV_SHOW' => 'N',
    'SLIDER_NAV_VIEW' => 1,
    'SLIDER_DOTS_SHOW' => 'N',
    'SLIDER_DOTS_VIEW' => 1,
    'SLIDER_LOOP' => 'Y',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => 10000,
    'SLIDER_AUTO_HOVER' => 'N',
    'ATTRIBUTE' => null,
    '~ATTRIBUTE' => null,
    'SELECTOR' => null,
    '~SELECTOR' => null,
    'ORDER_SHOW' => 'N',
    'ORDER_FORM_ID' => null,
    'ORDER_FORM_TEMPLATE' => null,
    'ORDER_FORM_FIELD' => null,
    'ORDER_FORM_TITLE' => null,
    'ORDER_FORM_CONSENT' => null,
    'ORDER_BUTTON' => null
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'HEIGHT' => Type::toInteger($arParams['HEIGHT']),
    'HEADER' => [
        'SHOW' => $arParams['HEADER_SHOW'] === 'Y' && !empty($arParams['PROPERTY_HEADER']),
        'H1' => $arParams['HEADER_H1'] === 'Y',
        'VIEW' => ArrayHelper::fromRange([1, 2, 3, 4, 5], $arParams['HEADER_VIEW']),
        'OVER' => [
            'SHOW' => $arParams['HEADER_OVER_SHOW'] === 'Y' && !empty($arParams['PROPERTY_HEADER_OVER']),
            'VIEW' => ArrayHelper::fromRange([1], $arParams['HEADER_OVER_VIEW'])
        ]
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y' && !empty($arParams['PROPERTY_DESCRIPTION']),
        'VIEW' => ArrayHelper::fromRange([1, 2, 3, 4, 5], $arParams['DESCRIPTION_VIEW'])
    ],
    'BUTTON' => [
        'VIEW' => ArrayHelper::fromRange([1, 2, 3, 4], $arParams['BUTTON_VIEW'])
    ],
    'PICTURE' => [
        'SHOW' => $arParams['PICTURE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PICTURE'])
    ],
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y' && !empty($arParams['PROPERTY_VIDEO'])
    ],
    'ADDITIONAL' => [
        'SHOW' => $arParams['ADDITIONAL_SHOW'] === 'Y' && !empty($arParams['PROPERTY_ADDITIONAL']),
        'VIEW' => ArrayHelper::fromRange([1, 2, 3], $arParams['ADDITIONAL_VIEW'])
    ],
    'BUTTONS' => [
        'BACK' => [
            'SHOW' => $arParams['BUTTONS_BACK_SHOW'] === 'Y',
            'LINK' => $arParams['BUTTONS_BACK_LINK']
        ]
    ],
    'SLIDER' => [
        'NAV' => [
            'SHOW' => $arParams['SLIDER_NAV_SHOW'] === 'Y',
            'VIEW' => ArrayHelper::fromRange([1], $arParams['SLIDER_NAV_VIEW'])
        ],
        'DOTS' => [
            'SHOW' => $arParams['SLIDER_DOTS_SHOW'] === 'Y',
            'VIEW' => ArrayHelper::fromRange([1, 2], $arParams['SLIDER_DOTS_VIEW'])
        ],
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_USE'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTO_TIME']),
            'HOVER' => $arParams['SLIDER_AUTO_HOVER'] === 'Y'
        ]
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['HEIGHT'] < 1)
    $arVisual['HEIGHT'] = 600;

if (!empty($arVisual['BUTTONS']['BACK']['LINK']))
    $arVisual['BUTTONS']['BACK']['LINK'] = StringHelper::replaceMacros(
        $arVisual['BUTTONS']['BACK']['LINK'],
        ['SITE_DIR' => SITE_DIR]
    );
else
    $arVisual['BUTTONS']['BACK']['SHOW'] = false;

if ($arVisual['SLIDER']['AUTO']['TIME'] < 1)
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arForm = [
    'SHOW' => $arParams['ORDER_SHOW'] === 'Y',
    'ID' => $arParams['ORDER_FORM_ID'],
    'TEMPLATE' => $arParams['ORDER_FORM_TEMPLATE'],
    'FIELD' => $arParams['ORDER_FORM_FIELD'],
    'NAME' => $arParams['ORDER_FORM_TITLE'],
    'CONSENT' => $arParams['ORDER_FORM_CONSENT'],
    'BUTTON' => $arParams['ORDER_BUTTON']
];

if (empty($arForm['BUTTON']))
    $arForm['BUTTON'] = Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_FORM_BUTTON_TEXT_DEFAULT');

if ($arForm['SHOW'])
    if (empty($arForm['ID']) || empty($arForm['TEMPLATE']))
        $arForm['SHOW'] = false;

$arResult['FORM'] = $arForm;

unset($arForm);

$arFiles = Collection::from([]);
$arPictures = Collection::from([]);

$bAdditionalShow = false;

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'NAME' => $arItem['NAME'],
        'OVER' => null,
        'HEADER' => $arItem['NAME'],
        'DESCRIPTION' => null,
        'LINK' => [
            'BLANK' => false,
            'VALUE' => null
        ],
        'BUTTON' => [
            'SHOW' => false,
            'TEXT' => null
        ],
        'TEXT' => [
            'POSITION' => 'left',
            'ALIGN' => 'left',
            'HALF' => false
        ],
        'PICTURE' => [
            'SHOW' => false,
            'VALUE' => null,
            'ALIGN' => [
                'VERTICAL' => 'center'
            ]
        ],
        'ADDITIONAL' => [],
        'SCHEME' => 'white',
        'FADE' => false,
        'VIDEO' => [
            'LINK' => null,
            'FILES' => [
                'MP4' => null,
                'WEBM' => null,
                'OGV' => null
            ]
        ]
    ];

    if (!empty($arParams['PROPERTY_HEADER_OVER'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_HEADER_OVER']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (Type::isArray($arProperty['DISPLAY_VALUE']))
                $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

            $arItem['DATA']['OVER'] = $arProperty['DISPLAY_VALUE'];
        }
    }

    if (!empty($arParams['PROPERTY_HEADER'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_HEADER']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            $arProperty = $arProperty['DISPLAY_VALUE'];

            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['HEADER'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_DESCRIPTION'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_DESCRIPTION']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            $arProperty = $arProperty['DISPLAY_VALUE'];

            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['DESCRIPTION'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_LINK'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_LINK'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['LINK']['VALUE'] = StringHelper::replaceMacros($arProperty, [
                'SITE_DIR' => SITE_DIR
            ]);
    }

    if (!empty($arParams['PROPERTY_LINK_BLANK'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_LINK_BLANK'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['LINK']['BLANK'] = true;
    }

    if (!empty($arParams['PROPERTY_BUTTON_SHOW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BUTTON_SHOW'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty) && !empty($arItem['DATA']['LINK']['VALUE']))
            $arItem['DATA']['BUTTON']['SHOW'] = true;
    }

    if (!empty($arParams['PROPERTY_BUTTON_TEXT'])) {
        $arProperty = $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BUTTON_TEXT'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['BUTTON']['TEXT'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_TEXT_POSITION'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TEXT_POSITION'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['TEXT']['POSITION'] = ArrayHelper::fromRange([
                'left',
                'center',
                'right'
            ], $arProperty);
        }
    }

    if (!empty($arParams['PROPERTY_TEXT_ALIGN'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TEXT_ALIGN'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['TEXT']['ALIGN'] = ArrayHelper::fromRange([
                'left',
                'center',
                'right'
            ], $arProperty);
        }
    }

    if (!empty($arParams['PROPERTY_TEXT_HALF'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TEXT_HALF'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['TEXT']['HALF'] = true;
    }

    if (!empty($arParams['PROPERTY_PICTURE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PICTURE'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            if (!empty($arProperty) && !$arPictures->has($arProperty)) {
                $arPictures->add($arProperty);

                $arItem['DATA']['PICTURE']['VALUE'] = $arProperty;
                $arItem['DATA']['PICTURE']['SHOW'] = $arResult['VISUAL']['PICTURE']['SHOW'];
            }
        }
    }

    if (!empty($arParams['PROPERTY_PICTURE_ALIGN_VERTICAL'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PICTURE_ALIGN_VERTICAL'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['PICTURE']['ALIGN']['VERTICAL'] = ArrayHelper::fromRange([
                'center',
                'top',
                'bottom'
            ], $arProperty);
        }
    }

    if (!empty($arParams['PROPERTY_ADDITIONAL'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_ADDITIONAL']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE'])) {
                    foreach ($arProperty['DISPLAY_VALUE'] as $sKey => $arValue)
                        $arItem['DATA']['ADDITIONAL'][] = [
                            'NAME' => $arValue,
                            'DESCRIPTION' => ArrayHelper::getValue($arProperty, ['~DESCRIPTION', $sKey])
                        ];

                    unset($arValue, $sKey);
                } else {
                    $arItem['DATA']['ADDITIONAL'][] = [
                        'NAME' => $arProperty['VALUE'][0],
                        'DESCRIPTION' => ArrayHelper::getValue($arProperty, ['~DESCRIPTION', 0])
                    ];
                }
            }
        }
    }

    if (!empty($arParams['PROPERTY_SCHEME'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_SCHEME'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['SCHEME'] = 'black';
    }

    if (!empty($arParams['PROPERTY_FADE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_FADE'],
            'VALUE'
        ]);

        if (!empty($arProperty) && !Type::isArray($arProperty))
            $arItem['DATA']['FADE'] = true;
    }

    if (!empty($arParams['PROPERTY_VIDEO'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_VIDEO'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            if (!empty($arProperty) && Type::isString($arProperty))
                $arItem['DATA']['VIDEO']['LINK'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_VIDEO_FILE_MP4'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_VIDEO_FILE_MP4'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            if (!empty($arProperty))
                $arItem['DATA']['VIDEO']['FILES']['MP4'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_VIDEO_FILE_WEBM'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_VIDEO_FILE_WEBM'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            if (!empty($arProperty))
                $arItem['DATA']['VIDEO']['FILES']['WEBM'] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_VIDEO_FILE_OGV'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_VIDEO_FILE_OGV'],
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            if (!empty($arProperty))
                $arItem['DATA']['VIDEO']['FILES']['OGV'] = $arProperty;
        }
    }

    foreach ($arItem['DATA']['VIDEO']['FILES'] as $sFile)
        if (!empty($sFile) && !$arFiles->has($sFile))
            $arFiles->add($sFile);

    if (!empty($arItem['DATA']['ADDITIONAL']))
        $bAdditionalShow = true;
}

unset($arItem, $arProperty);

if (!$arFiles->isEmpty()) {
    $arFiles = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arFiles->asArray())
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arFiles = Arrays::from([]);
}

if (!$arPictures->isEmpty()) {
    $arPictures = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arPictures->asArray())
    ]))->each(function ($iIndex, &$arFile) {
        $arFile['SRC'] = CFile::GetFileSRC($arFile);
    })->indexBy('ID');
} else {
    $arPictures = Arrays::from([]);
}

if ($arPictures->isEmpty() || !$arFiles->isEmpty()) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!$arFiles->isEmpty()) {
            $arItemFiles = $arItem['DATA']['VIDEO']['FILES'];

            foreach ($arItemFiles as $sType => $sItemFile)
                if ($arFiles->exists($sItemFile)) {
                    $arItem['DATA']['VIDEO']['FILES'][$sType] = $arFiles->get($sItemFile);
                } else {
                    unset($arItem['DATA']['VIDEO']['FILES'][$sType]);
                }

            unset($sType, $sItemFile, $arItemFiles);
        }

        if (!$arPictures->isEmpty()) {
            $arItemPicture = $arItem['DATA']['PICTURE']['VALUE'];

            if (!empty($arItemPicture) && $arPictures->exists($arItemPicture))
                $arItem['DATA']['PICTURE']['VALUE'] = $arPictures->get($arItemPicture);

            unset($arItemPicture);
        }
    }
}

unset($arFiles, $arItem);

if (!$bAdditionalShow)
    $arResult['VISUAL']['ADDITIONAL']['SHOW'] = false;