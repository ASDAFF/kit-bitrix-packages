<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PROPERTY_BANNER_TYPE' => null,
    'PROPERTY_BANNER_URL' => null,
    'PROPERTY_BANNER_IMAGE' => null,
    'PROPERTY_CHARACTERISTICS' => [],
    'PROPERTY_INFORMATION' => [],
    'PROPERTY_FEATURES' => [],
    'PROPERTY_EXAMPLE' => [],
    'PROPERTY_RESULT' => null,
    'PROPERTY_REVIEW' => null,
    'PROPERTY_SERVICES' => null,
    'SERVICES_IBLOCK_TYPE' => null,
    'SERVICES_IBLOCK_ID' => null,
    'PROPERTY_PROJECTS' => null,
    'SERVICES_PROPERTY_LINK' => null,
    'BANNER_SHOW' => 'N',
    'BANNER_HEIGHT' => 650,
    'BANNER_TYPE' => 'N',
    'BANNER_URL' => 'N',
    'BANNER_BUTTON' => null,
    'BANNER_IMAGE' => 'N',
    'CHARACTERISTICS_SHOW' => 'N',
    'CHARACTERISTICS_COLUMNS' => 4,
    'INFORMATION_SHOW' => 'N',
    'INFORMATION_COLUMNS' => 2,
    'FEATURES_SHOW' => 'N',
    'FEATURES_NARROW' => 'N',
    'EXAMPLE_SHOW' => 'N',
    'EXAMPLE_SHADOW' => 'N',
    'RESULT_SHOW' => 'N',
    'RESULT_NARROW' => 'N',
    'RESULT_BACKGROUND' => 'N',
    'REVIEW_SHOW' => 'N',
    'REVIEW_NARROW' => 'N',
    'SERVICES_SHOW' => 'N',
    'PROJECTS_SHOW' => 'N'
], $arParams);

$arParams['PROPERTY_CHARACTERISTICS'] = array_filter($arParams['PROPERTY_CHARACTERISTICS']);
$arParams['PROPERTY_INFORMATION'] = array_filter($arParams['PROPERTY_INFORMATION']);
$arParams['PROPERTY_FEATURES'] = array_filter($arParams['PROPERTY_FEATURES']);
$arParams['PROPERTY_EXAMPLE'] = array_filter($arParams['PROPERTY_EXAMPLE']);

$arData = [
    'BANNER' => [],
    'CHARACTERISTICS' => [],
    'INFORMATION' => [],
    'FEATURES' => [],
    'EXAMPLE' => [],
    'RESULT' => [],
    'SERVICES' => [],
    'PROJECTS' => [],
    'REVIEW' => []
];

$hGetDisplayValue = function ($property) use (&$arResult) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $property
    ]);

    if (!empty($arProperty)) {
        $arProperty = CIBlockFormatProperties::GetDisplayValue(
            $arResult,
            $arProperty,
            'newsDetailPortfolio'
        );

        return $arProperty;
    }

    return null;
};

if (!empty($arParams['PROPERTY_BANNER_TYPE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BANNER_TYPE'],
        'VALUE'
    ]);

    if (!empty($arProperty)) {
        if (Type::isArray($arProperty))
            $arProperty = ArrayHelper::getFirstValue($arProperty);

        $arData['BANNER']['TYPE'] = $arProperty;
    }
}

if (!empty($arParams['PROPERTY_BANNER_URL'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BANNER_URL'],
        'VALUE'
    ]);

    if (!empty($arProperty)) {
        if (Type::isArray($arProperty))
            $arProperty = ArrayHelper::getFirstValue($arProperty);

        $arData['BANNER']['URL'] = $arProperty;
    }

    if (!empty($arParams['BANNER_BUTTON'])) {
        $arData['BANNER']['BUTTON'] = $arParams['BANNER_BUTTON'];
    }
}

if (!empty($arParams['PROPERTY_BANNER_IMAGE'])) {
    $arProperty = $hGetDisplayValue($arParams['PROPERTY_BANNER_IMAGE']);

    if (!empty($arProperty['FILE_VALUE'])) {
        $arData['BANNER']['IMAGE'] = $arProperty['FILE_VALUE'];
    }
}

if (!empty($arParams['PROPERTY_CHARACTERISTICS']) && Type::isArray($arParams['PROPERTY_CHARACTERISTICS'])) {
    foreach ($arParams['PROPERTY_CHARACTERISTICS'] as $sCharacteristic) {
        $arProperty = $hGetDisplayValue($sCharacteristic);

        if (!empty($arProperty['DISPLAY_VALUE']))
            $arData['CHARACTERISTICS'][] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];
    }

    unset($sCharacteristic);
}

if (!empty($arParams['PROPERTY_INFORMATION']) && Type::isArray($arParams['PROPERTY_INFORMATION'])) {
    foreach ($arParams['PROPERTY_INFORMATION'] as $sInformation) {
        $arProperty = $hGetDisplayValue($sInformation);

        if (!empty($arProperty['DISPLAY_VALUE']))
            $arData['INFORMATION'][] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];
    }

    unset($sInformation);
}

if (!empty($arParams['PROPERTY_FEATURES']) && Type::isArray($arParams['PROPERTY_FEATURES'])) {
    foreach ($arParams['PROPERTY_FEATURES'] as $sFeature) {
        $arProperty = $hGetDisplayValue($sFeature);

        if (!empty($arProperty['DISPLAY_VALUE']))
            $arData['FEATURES'][] = [
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];
    }

    unset($sFeature);
}

if (!empty($arParams['PROPERTY_EXAMPLE']) && Type::isArray($arParams['PROPERTY_EXAMPLE'])) {
    foreach ($arParams['PROPERTY_EXAMPLE'] as $sExample) {
        $arProperty = $hGetDisplayValue($sExample);

        if (!empty($arProperty['FILE_VALUE'])) {
            $arImages = [];

            if ($arProperty['MULTIPLE'] === 'Y') {
                if (Type::isArray(ArrayHelper::getFirstValue($arProperty['FILE_VALUE']))) {
                    foreach ($arProperty['FILE_VALUE'] as $sKey => $arFile)
                        $arImages[] = [
                            'VALUE' => $arFile['SRC'],
                            'DESCRIPTION' => $arProperty['DESCRIPTION'][$sKey]
                        ];
                } else {
                    $arImages[] = [
                        'VALUE' => $arProperty['FILE_VALUE']['SRC'],
                        'DESCRIPTION' => ArrayHelper::getFirstValue($arProperty['DESCRIPTION'])
                    ];
                }
            } else {
                $arImages[] = [
                    'VALUE' => $arProperty['FILE_VALUE']['SRC'],
                    'DESCRIPTION' => $arProperty['DESCRIPTION']
                ];
            }

            $arData['EXAMPLE'][] = [
                'FILE' => true,
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arImages
            ];

            unset($arFile, $arImages);
        } else
            $arData['EXAMPLE'][] = [
                'FILE' => false,
                'NAME' => $arProperty['NAME'],
                'VALUE' => $arProperty['DISPLAY_VALUE']
            ];
    }

    unset($sExample);
}

if (!empty($arParams['PROPERTY_RESULT'])) {
    $arProperty = $hGetDisplayValue($arParams['PROPERTY_RESULT']);

    if (!empty($arProperty['DISPLAY_VALUE']))
        $arData['RESULT'] = [
            'NAME' => $arProperty['NAME'],
            'VALUE' => $arProperty['DISPLAY_VALUE']
        ];
}

if (!empty($arParams['PROPERTY_REVIEW'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_REVIEW']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if (Type::isArray($arProperty['VALUE']))
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $rsReview = CIBlockElement::GetByID($arProperty['VALUE']);

        $arReview = $rsReview->GetNext();

        unset($rsReview);

        if (!empty($arReview)) {
            $arValue = [
                'NAME' => $arReview['NAME'],
                'PICTURE' => $arReview['DETAIL_PICTURE'],
                'TEXT' => $arReview['DETAIL_TEXT'],
            ];

            if (empty($arValue['PICTURE']))
                $arValue['PICTURE'] = $arReview['PREVIEW_PICTURE'];

            if (!empty($arValue['PICTURE'])) {
                $arValue['PICTURE'] = CFile::GetByID($arValue['PICTURE'])->GetNext();

                if (!empty($arValue['PICTURE']))
                    $arValue['PICTURE']['SRC'] = CFile::GetFileSRC($arValue['PICTURE']);
            }

            if (empty($arValue['TEXT']))
                $arValue['TEXT'] = $arReview['PREVIEW_TEXT'];

            if (!empty($arValue['TEXT']))
                $arData['REVIEW'] = [
                    'NAME' => $arProperty['NAME'],
                    'VALUE' => $arValue
                ];

            unset($arValue);
        }

        unset($arReview);
    }
}

if (!empty($arParams['PROPERTY_SERVICES'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_SERVICES']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if (Type::isArray($arProperty['VALUE']))
            $arProperty['VALUE'] = array_filter($arProperty['VALUE']);

        $arData['SERVICES'] = [
            'NAME' => $arProperty['NAME'],
            'VALUE' => $arProperty['VALUE']
        ];
    }
}

if (!empty($arParams['PROPERTY_PROJECTS'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_PROJECTS']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if (Type::isArray($arProperty['VALUE']))
            $arProperty['VALUE'] = array_filter($arProperty['VALUE']);

        $arData['PROJECTS'] = [
            'NAME' => $arProperty['NAME'],
            'VALUE' => $arProperty['VALUE']
        ];
    }
}

unset($hGetDisplayValue, $arProperty);

$arVisual = [
    'BANNER' => [
        'SHOW' => $arParams['BANNER_SHOW'] === 'Y',
        'HEIGHT' => Type::toInteger($arParams['BANNER_HEIGHT']),
        'TYPE' => $arParams['BANNER_TYPE'] === 'Y' && !empty($arData['BANNER']['TYPE']),
        'URL' => $arParams['BANNER_URL'] === 'Y' && !empty($arData['BANNER']['URL']),
        'IMAGE' => $arParams['BANNER_IMAGE'] === 'Y' && !empty($arData['BANNER']['IMAGE'])
    ],
    'CHARACTERISTICS' => [
        'SHOW' => $arParams['CHARACTERISTICS_SHOW'] === 'Y' && !empty($arData['CHARACTERISTICS']),
        'COLUMNS' => ArrayHelper::fromRange([4, 3, 5], $arParams['CHARACTERISTICS_COLUMNS'])
    ],
    'INFORMATION' => [
        'SHOW' => $arParams['INFORMATION_SHOW'] === 'Y' && !empty($arData['INFORMATION']),
        'COLUMNS' => ArrayHelper::fromRange([2, 1, 3], $arParams['INFORMATION_COLUMNS'])
    ],
    'FEATURES' => [
        'SHOW' => $arParams['FEATURES_SHOW'] === 'Y' && !empty($arData['FEATURES']),
        'NARROW' => $arParams['FEATURES_NARROW'] === 'Y'
    ],
    'EXAMPLE' => [
        'SHOW' => $arParams['EXAMPLE_SHOW'] === 'Y' && !empty($arData['EXAMPLE']),
        'SHADOW' => $arParams['EXAMPLE_SHADOW'] === 'Y'
    ],
    'RESULT' => [
        'SHOW' => $arParams['RESULT_SHOW'] === 'Y' && !empty($arData['RESULT']),
        'NARROW' => $arParams['RESULT_NARROW'] === 'Y',
        'BACKGROUND' => $arParams['RESULT_BACKGROUND'] === 'Y'
    ],
    'REVIEW' => [
        'SHOW' => $arParams['REVIEW_SHOW'] === 'Y' && !empty($arData['REVIEW']),
        'NARROW' => $arParams['REVIEW_NARROW'] === 'Y'
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y' && !empty($arData['SERVICES']) && !empty($arParams['SERVICES_IBLOCK_ID'])
    ],
    'PROJECTS' => [
        'SHOW' => $arParams['PROJECTS_SHOW'] === 'Y' && !empty($arData['PROJECTS'])
    ]
];

$arResult['DATA'] = $arData;
$arResult['VISUAL'] = $arVisual;
//print_r($arData['BANNER']);
unset($arData, $arVisual);