<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\base\Collection;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'BLOCKS_USE' => 'N',
    'BLOCKS_IBLOCK_TYPE' => null,
    'BLOCKS_IBLOCK_ID' => null,
    'BLOCKS_MODE' => 'N',
    'BLOCKS_SECTIONS' => [],
    'BLOCKS_ELEMENTS_COUNT' => 2,
    'PROPERTY_HEADER_OVER' => null,
    'PROPERTY_LINK' => null,
    'PROPERTY_LINK_BLANK' => null,
    'PROPERTY_BUTTON_SHOW' => null,
    'PROPERTY_BUTTON_TEXT' => null,
    'PROPERTY_TEXT_ALIGN' => null,
    'PROPERTY_TEXT_HALF' => null,
    'PROPERTY_PICTURE' => null,
    'PROPERTY_PICTURE_ALIGN_VERTICAL' => null,
    'PROPERTY_FADE' => null,
    'PROPERTY_SCHEME' => null,
    'PROPERTY_VIDEO' => null,
    'PROPERTY_VIDEO_FILE_MP4' => null,
    'PROPERTY_VIDEO_FILE_WEBM' => null,
    'PROPERTY_VIDEO_FILE_OGV' => null,
    'BLOCKS_PROPERTY_LINK' => null,
    'BLOCKS_PROPERTY_LINK_BLANK' => null,
    'HEIGHT' => 600,
    'HEADER_SHOW' => 'N',
    'HEADER_VIEW' => 1,
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_VIEW' => 1,
    'HEADER_OVER_SHOW' => 'N',
    'HEADER_OVER_VIEW' => 1,
    'BUTTON_VIEW' => 1,
    'PICTURE_SHOW' => 'N',
    'VIDEO_SHOW' => 'N',
    'BLOCKS_POSITION' => 'right',
    'BLOCKS_EFFECT_FADE' => 'N',
    'BLOCKS_EFFECT_SCALE' => 'N',
    'BLOCKS_INDENT' => 'N',
    'ROUNDED' => 'N',
    'SLIDER_NAV_SHOW' => 'N',
    'SLIDER_NAV_VIEW' => 1,
    'SLIDER_DOTS_SHOW' => 'N',
    'SLIDER_DOTS_VIEW' => 1,
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => 10000,
    'SLIDER_AUTO_HOVER' => 'N',
    'ATTRIBUTE' => null,
    '~ATTRIBUTE' => null,
    'SELECTOR' => null,
    '~SELECTOR' => null
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'HEIGHT' => Type::toInteger($arParams['HEIGHT']),
    'OVER' => [
        'SHOW' => $arParams['HEADER_OVER_SHOW'] === 'Y' && !empty($arParams['PROPERTY_HEADER_OVER']),
        'VIEW' => ArrayHelper::fromRange([1], $arParams['HEADER_OVER_VIEW'])
    ],
    'HEADER' => [
        'SHOW' => $arParams['HEADER_SHOW'] === 'Y' && !empty($arParams['PROPERTY_HEADER']),
        'VIEW' => ArrayHelper::fromRange([1, 2, 3, 4, 5], $arParams['HEADER_VIEW'])
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
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y'
    ],
    'BLOCKS' => [
        'USE' => $arParams['BLOCKS_USE'] === 'Y' && !empty($arParams['BLOCKS_IBLOCK_ID']),
        'POSITION' => ArrayHelper::fromRange(['right', 'left', 'both'], $arParams['BLOCKS_POSITION']),
        'HALF' => false,
        'EFFECT' => [
            'FADE' => $arParams['BLOCKS_EFFECT_FADE'] === 'Y',
            'SCALE' => $arParams['BLOCKS_EFFECT_SCALE'] === 'Y'
        ],
        'INDENT' => $arParams['BLOCKS_INDENT'] === 'Y'
    ],
    'ROUNDED' => $arParams['ROUNDED'] === 'Y',
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

if ($arVisual['BLOCKS']['INDENT'] && !$arVisual['BLOCKS']['USE'])
    $arVisual['BLOCKS']['INDENT'] = false;

if ($arVisual['ROUNDED'] && $arVisual['BLOCKS']['USE'] && !$arVisual['BLOCKS']['INDENT'])
    $arVisual['ROUNDED'] = false;

if ($arVisual['SLIDER']['AUTO']['TIME'] < 1)
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arFiles = Collection::from([]);
$arPictures = Collection::from([]);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'OVER' => null,
        'HEADER' => $arItem['NAME'],
        'DESCRIPTION' => null,
        'LINK' => [
            'VALUE' => null,
            'BLANK' => false
        ],
        'BUTTON' => [
            'SHOW' => false,
            'TEXT' => null
        ],
        'TEXT' => [
            'ALIGN' => 'left',
            'HALF' => false
        ],
        'PICTURE' => [
            'VALUE' => null,
            'ALIGN' => [
                'VERTICAL' => 'middle'
            ]
        ],
        'FADE' => false,
        'SCHEME' => 'white',
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

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['OVER'] = $arProperty['DISPLAY_VALUE'];
            }
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

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['HEADER'] = $arProperty['DISPLAY_VALUE'];
            }
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

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['DESCRIPTION'] = $arProperty['DISPLAY_VALUE'];
            }
        }
    }

    if (!empty($arParams['PROPERTY_LINK'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_LINK']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['LINK']['VALUE'] = StringHelper::replaceMacros(
                    $arProperty['DISPLAY_VALUE'],
                    ['SITE_DIR' => SITE_DIR]
                );
            }
        }
    }

    if (!empty($arParams['PROPERTY_LINK_BLANK'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_LINK_BLANK'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty))
            $arItem['DATA']['LINK']['BLANK'] = true;
    }

    if (!empty($arParams['PROPERTY_BUTTON_SHOW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BUTTON_SHOW'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty) && !empty($arItem['DATA']['LINK']['VALUE']))
            $arItem['DATA']['BUTTON']['SHOW'] = true;
    }

    if (!empty($arParams['PROPERTY_BUTTON_TEXT'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BUTTON_TEXT']
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arProperty = CIBlockFormatProperties::GetDisplayValue(
                $arItem,
                $arProperty,
                false
            );

            if (!empty($arProperty['DISPLAY_VALUE'])) {
                if (Type::isArray($arProperty['DISPLAY_VALUE']))
                    $arProperty['DISPLAY_VALUE'] = ArrayHelper::getFirstValue($arProperty['DISPLAY_VALUE']);

                $arItem['DATA']['BUTTON']['TEXT'] = $arProperty['DISPLAY_VALUE'];
            }
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
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty))
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

            if (!$arPictures->has($arProperty)) {
                $arPictures->add($arProperty);

                $arItem['DATA']['PICTURE']['VALUE'] = $arProperty;
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
                'middle',
                'top',
                'bottom'
            ], $arProperty);
        }
    }

    if (!empty($arParams['PROPERTY_FADE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_FADE'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty))
            $arItem['DATA']['FADE'] = true;
    }

    if (!empty($arParams['PROPERTY_SCHEME'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_SCHEME'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty))
            $arItem['DATA']['SCHEME'] = 'black';
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

            if (Type::isString($arProperty))
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

    foreach ($arItem['DATA']['VIDEO']['FILES'] as $sFile) {
        if (!empty($sFile) && !$arFiles->has($sFile))
            $arFiles->add($sFile);
    }
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
    ]))->each(function ($iIndex, &$arPicture) {
        $arPicture['SRC'] = CFile::GetFileSRC($arPicture);
    })->indexBy('ID');
} else {
    $arPictures = Arrays::from([]);
}

if (!$arFiles->isEmpty() || !$arPictures->isEmpty()) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!$arFiles->isEmpty()) {
            $arItemFiles = $arItem['DATA']['VIDEO']['FILES'];

            foreach ($arItemFiles as $sType => $sItemFile) {
                if (!empty($sItemFile) && $arFiles->exists($sItemFile))
                    $arItem['DATA']['VIDEO']['FILES'][$sType] = $arFiles->get($sItemFile);
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

unset($arFiles, $arPictures, $arItem);

if ($arResult['VISUAL']['BLOCKS']['USE'])
    include(__DIR__.'/modifiers/blocks.php');