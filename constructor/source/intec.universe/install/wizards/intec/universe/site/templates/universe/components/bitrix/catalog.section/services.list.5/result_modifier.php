<?php if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true);

/**
 * @var array $arResult
 * @var array $arParams
 */

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

if (empty($arResult['ITEMS']))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_BACKGROUND_IMAGE' => null,
    'PROPERTY_BACKGROUND_COLOR' => null,
    'PROPERTY_IMAGE' => null,
    'PROPERTY_TYPE' => null,
    'PROPERTY_LOGOTYPE' => null,
    'PROPERTY_PRICE' => null,
    'PROPERTY_PRICE_OLD' => null,
    'PROPERTY_BUTTON_SHOW' => null,
    'PROPERTY_BUTTON_URL' => null,
    'PROPERTY_BUTTON_TEXT' => null,
    'PROPERTY_MARKS' => null,
    'PROPERTY_TABS' => null,
    'PROPERTY_THEME' => null,
    'IMAGE_SHOW' => 'N',
    'TYPE_SHOW' => 'N',
    'LOGOTYPE_SHOW' => 'N',
    'PREVIEW_SHOW' => 'N',
    'PRICE_SHOW' => 'N',
    'PRICE_OLD_SHOW' => 'N',
    'MARKS_SHOW' => 'N',
    'TABS_USE' => 'N',
    'TABS_POSITION' => 'center',
    'LAZY_LOAD' => 'N',
    'LOAD_ON_SCROLL' => 'N',
    'WIDE' => 'N'
], $arParams);

$arVisual = [
    'NAVIGATION' => [
        'TOP' => [
            'SHOW' => $arParams['DISPLAY_TOP_PAGER']
        ],
        'BOTTOM' => [
            'SHOW' => $arParams['DISPLAY_BOTTOM_PAGER']
        ],
        'LAZY' => [
            'BUTTON' => $arParams['LAZY_LOAD'] === 'Y',
            'SCROLL' => $arParams['LOAD_ON_SCROLL'] === 'Y'
        ]
    ],
    'IMAGE' => [
        'SHOW' => $arParams['IMAGE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_IMAGE'])
    ],
    'TYPE' => [
        'SHOW' => $arParams['TYPE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TYPE'])
    ],
    'LOGOTYPE' => [
        'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_LOGOTYPE'])
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ],
    'PRICE' => [
        'SHOW' => [
            'CURRENT' => $arParams['PRICE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PRICE']),
            'OLD' => $arParams['PRICE_OLD_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PRICE_OLD'])
        ]
    ],
    'MARKS' => [
        'SHOW' => $arParams['MARKS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_MARKS'])
    ],
    'TABS' => [
        'USE' => $arParams['TABS_USE'] === 'Y' && !empty($arParams['PROPERTY_TABS']),
        'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['TABS_POSITION'])
    ],
    'WIDE' => $arParams['WIDE'] === 'Y',
];

$arResult['VISUAL'] = $arVisual;
$arResult['TABS'] = [];

$arImages = [];

$hGetImageId = function ($properties = [], &$arItem) use (&$arImages) {
    if (!empty($properties) && Type::isArray($properties)) {
        foreach ($properties as $property) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $property
            ]);

            if (!empty($arProperty['VALUE'])) {
                if ($arProperty['MULTIPLE'] === 'Y') {
                    foreach ($arProperty['VALUE'] as $sValue) {
                        if (!ArrayHelper::isIn($sValue, $arImages))
                            $arImages[] = $sValue;
                    }
                } else {
                    if (!ArrayHelper::isIn($arProperty['VALUE'], $arImages))
                        $arImages[] = $arProperty['VALUE'];
                }
            }
        }
    }
};

$arProperties = [
    $arParams['PROPERTY_BACKGROUND_IMAGE'],
    $arParams['PROPERTY_IMAGE'],
    $arParams['PROPERTY_LOGOTYPE'],
];

foreach ($arResult['ITEMS'] as &$arItem) {
    $hGetImageId($arProperties, $arItem);
}

unset($arItem);

if (!empty($arImages)) {
    $arImages = Arrays::fromDBResult(CFile::GetList([], [
        '@ID' => implode(',', $arImages)
    ]))->indexBy('ID');
} else {
    $arImages = Arrays::from($arImages);
}

$arTabs = [];

if (!empty($arParams['PROPERTY_TABS'])) {
    $arProperty = ArrayHelper::getFirstValue($arResult['ITEMS']);

    if (!empty($arProperty)) {
        $arProperty = ArrayHelper::getValue($arProperty, [
            'PROPERTIES',
            $arParams['PROPERTY_TABS']
        ]);

        if (!empty($arProperty)) {
            $arProperty = Arrays::fromDBResult(CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'PROPERTY_ID' => $arProperty['ID']
            ]))->indexBy('ID');

            if (!$arProperty->isEmpty()) {
                $arTabs = $arProperty->asArray(function ($key, $value) {
                    return [
                        'key' => $value['XML_ID'],
                        'value' => [
                            'SHOW' => false,
                            'VALUE' => $value['VALUE']
                        ]
                    ];
                });
            }
        }
    }

    unset($arProperty);
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'BACKGROUND' => [],
        'IMAGE' => [],
        'TYPE' => null,
        'LOGOTYPE' => [],
        'PRICE' => [],
        'BUTTON' => [
            'SHOW' => false
        ],
        'MARKS' => [],
        'TABS' => [],
        'THEME' => 'white'
    ];

    if (!empty($arParams['PROPERTY_BACKGROUND_IMAGE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BACKGROUND_IMAGE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            if ($arImages->exists($arProperty['VALUE'])) {
                $arItem['DATA']['BACKGROUND']['IMAGE'] = $arImages->get($arProperty['VALUE']);
                $arItem['DATA']['BACKGROUND']['IMAGE']['SRC'] = CFile::GetFileSRC($arItem['DATA']['BACKGROUND']['IMAGE']);
            }
        }
    }

    if (!empty($arParams['PROPERTY_BACKGROUND_COLOR'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BACKGROUND_COLOR']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            $arItem['DATA']['BACKGROUND']['COLOR'] = $arProperty['VALUE'];
        }
    }

    if (!empty($arItem['DATA']['BACKGROUND']['IMAGE'])) {
        $arItem['DATA']['BACKGROUND']['TYPE'] = 'image';
    } else {
        $arItem['DATA']['BACKGROUND']['TYPE'] = 'color';
    }

    if (!empty($arParams['PROPERTY_IMAGE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_IMAGE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            if ($arImages->exists($arProperty['VALUE'])) {
                $arItem['DATA']['IMAGE'] = $arImages->get($arProperty['VALUE']);
                $arItem['DATA']['IMAGE']['SRC'] = CFile::GetFileSRC($arItem['DATA']['IMAGE']);
            }
        }
    }

    if (empty($arItem['DATA']['IMAGE']) && !empty($arItem['PREVIEW_PICTURE'])) {
        $arItem['DATA']['IMAGE'] = $arItem['PREVIEW_PICTURE'];
    }

    if (!empty($arParams['PROPERTY_TYPE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TYPE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = implode(', ', $arProperty['VALUE']);

            $arItem['DATA']['TYPE'] = $arProperty['VALUE'];
        }
    }

    if (!empty($arParams['PROPERTY_LOGOTYPE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_LOGOTYPE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            if ($arImages->exists($arProperty['VALUE'])) {
                $arItem['DATA']['LOGOTYPE'] = $arImages->get($arProperty['VALUE']);
                $arItem['DATA']['LOGOTYPE']['SRC'] = CFile::GetFileSRC($arItem['DATA']['LOGOTYPE']);
            }
        }
    }

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            $arItem['DATA']['PRICE']['CURRENT'] = $arProperty['VALUE'];
        }
    }

    if (!empty($arParams['PROPERTY_PRICE_OLD'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE_OLD']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

            $arItem['DATA']['PRICE']['OLD'] = $arProperty['VALUE'];
        }
    }

    if (!empty($arParams['PROPERTY_BUTTON_SHOW'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_BUTTON_SHOW']
        ]);

        if (!empty($arProperty['VALUE_XML_ID']) && !Type::isArray($arProperty['VALUE_XML_ID'])) {
            $arItem['DATA']['BUTTON']['SHOW'] = true;
        }
    }

    if ($arItem['DATA']['BUTTON']['SHOW']) {
        if (!empty($arParams['PROPERTY_BUTTON_URL'])) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $arParams['PROPERTY_BUTTON_URL']
            ]);

            if (!empty($arProperty['VALUE'])) {
                if ($arProperty['MULTIPLE'] === 'Y')
                    $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

                $arItem['DATA']['BUTTON']['URL'] = StringHelper::replaceMacros($arProperty['VALUE'], [
                    'SITE_DIR' => SITE_DIR
                ]);
            }
        }

        if (!empty($arParams['PROPERTY_BUTTON_TEXT'])) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $arParams['PROPERTY_BUTTON_TEXT']
            ]);

            if (!empty($arProperty['VALUE'])) {
                if ($arProperty['MULTIPLE'] === 'Y')
                    $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

                $arItem['DATA']['BUTTON']['TEXT'] = $arProperty['VALUE'];
            }
        }

        if (empty($arItem['DATA']['BUTTON']['URL']))
            $arItem['DATA']['BUTTON']['SHOW'] = false;
    }

    if (!empty($arParams['PROPERTY_MARKS'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_MARKS']
        ]);

        if (!empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] !== 'Y')
                $arProperty['VALUE'] = explode(',', $arProperty['VALUE']);

            $arItem['DATA']['MARKS'] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['VALUE']
            ];
        }
    }

    if (!empty($arParams['PROPERTY_THEME'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_THEME']
        ]);

        if (!empty($arProperty['VALUE_XML_ID'])) {
            if ($arProperty['MULTIPLE'] === 'Y')
                $arProperty['VALUE_XML_ID'] = ArrayHelper::getFirstValue($arProperty['VALUE_XML_ID']);

            $arItem['DATA']['THEME'] = ArrayHelper::fromRange(['white', 'black'], $arProperty['VALUE_XML_ID']);
        }
    }

    if (!empty($arParams['PROPERTY_TABS'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TABS']
        ]);

        if (!empty($arProperty['VALUE_XML_ID']) && !empty($arProperty['VALUE'])) {
            if ($arProperty['MULTIPLE'] === 'Y') {
                foreach ($arProperty['VALUE_XML_ID'] as $sProperty) {
                    if (ArrayHelper::keyExists($sProperty, $arTabs)) {
                        if (!$arTabs[$sProperty]['SHOW']) {
                            $arTabs[$sProperty]['SHOW'] = true;
                        }
                    }

                    $arItem['DATA']['TABS'][$sProperty] = true;
                }
            } else {
                if (ArrayHelper::keyExists($arProperty['VALUE_XML_ID'], $arTabs)) {
                    if (!$arTabs[$arProperty['VALUE_XML_ID']]['SHOW']) {
                        $arTabs[$arProperty['VALUE_XML_ID']]['SHOW'] = true;
                    }

                    $arItem['DATA']['TABS'][$arProperty['VALUE_XML_ID']] = true;
                }
            }
        }
    }

    unset($arProperty);
}

unset($arItem);

if (!empty($arTabs)) {
    foreach ($arTabs as $key => $arTab) {
        if ($arTab['SHOW']) {
            $arResult['TABS'][$key] = $arTab['VALUE'];
        }
    }
}

unset($arTabs);