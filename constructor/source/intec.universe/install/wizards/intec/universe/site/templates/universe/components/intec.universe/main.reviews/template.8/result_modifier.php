<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'VIDEO_IBLOCK_TYPE' => null,
    'VIDEO_IBLOCK_ID' => null,
    'SERVICES_IBLOCK_TYPE' => null,
    'SERVICES_IBLOCK_ID' => null,
    'PROJECTS_IBLOCK_TYPE' => null,
    'PROJECTS_IBLOCK_ID' => null,
    'PROPERTY_POSITION' => null,
    'PROPERTY_VIDEO' => null,
    'VIDEO_PROPERTY_LINK' => null,
    'PROPERTY_SERVICES' => null,
    'PROPERTY_PROJECTS' => null,
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'POSITION_SHOW' => 'N',
    'VIDEO_SHOW' => 'N',
    'VIDEO_QUALITY' => 'sddefault',
    'SERVICES_SHOW' => 'N',
    'PROJECTS_SHOW' => 'N',
    'FOOTER_SHOW' => 'N',
    'FOOTER_POSITION' => 'center',
    'FOOTER_BUTTON_SHOW' => 'N',
    'FOOTER_BUTTON_TEXT' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arLink = [
    'VIDEO' => [],
    'SERVICES' => [],
    'PROJECTS' => []
];

$hGetProperty = function (&$arItem, $property, $asArray = false) {
    $property = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $property,
        'VALUE'
    ]);

    if (!empty($property)) {
        if (Type::isArray($property) && !$asArray)
            $property = ArrayHelper::getFirstValue($property);

        return $property;
    }

    return null;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [];

    if (!empty($arParams['PROPERTY_POSITION'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_POSITION']);

        if (!empty($arProperty))
            $arItem['DATA']['POSITION'] = $arProperty;
    }

    if (!empty($arParams['PROPERTY_VIDEO'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_VIDEO']);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arLink['VIDEO'][] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_SERVICES'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_SERVICES'], true);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                foreach ($arProperty as $sProperty)
                    $arLink['SERVICES'][] = $sProperty;
            else
                $arLink['SERVICES'][] = $arProperty;
        }
    }

    if (!empty($arParams['PROPERTY_PROJECTS'])) {
        $arProperty = $hGetProperty($arItem, $arParams['PROPERTY_PROJECTS'], true);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                foreach ($arProperty as $sProperty)
                    $arLink['PROJECTS'][] = $sProperty;
            else
                $arLink['PROJECTS'][] = $arProperty;
        }
    }
}

unset($arItem, $hGetProperty);

$arVideoData = [];

if (!empty($arLink['VIDEO']) && !empty($arParams['VIDEO_IBLOCK_ID']) && !empty($arParams['VIDEO_PROPERTY_LINK'])) {
    $rsVideos = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arParams['VIDEO_IBLOCK_ID'],
        'ID' => $arLink['VIDEO']
    ]);

    while ($arVideo = $rsVideos->GetNextElement()) {
        $arVideoTemp = $arVideo->GetFields();
        $arVideoTemp['PROPERTIES'] = $arVideo->GetProperties();

        $sValue = ArrayHelper::getValue($arVideoTemp, [
            'PROPERTIES',
            $arParams['VIDEO_PROPERTY_LINK'],
            'VALUE'
        ]);

        if (!empty($sValue)) {
            if (Type::isArray($sValue))
                $sValue = ArrayHelper::getFirstValue($sValue);

            $arVideoData[$arVideoTemp['ID']] = $sValue;
        }
    }

    unset($rsVideos, $arVideo, $arVideoTemp, $sValue);
}

$arServicesData = [];

if (!empty($arLink['SERVICES']) && !empty($arParams['SERVICES_IBLOCK_ID'])) {
    $rsServices = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arParams['SERVICES_IBLOCK_ID'],
        'ID' => $arLink['SERVICES']
    ]);

    while ($arService = $rsServices->GetNext())
        $arServicesData[$arService['ID']] = [
            'NAME' => $arService['NAME'],
            'URL' => $arService['DETAIL_PAGE_URL']
        ];

    unset($rsServices, $arService);
}

$arProjectsData = [];

if (!empty($arLink['PROJECTS']) && !empty($arParams['PROJECTS_IBLOCK_ID'])) {
    $rsProjects = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arParams['PROJECTS_IBLOCK_ID'],
        'ID' => $arLink['PROJECTS']
    ]);

    while ($arProject = $rsProjects->GetNext())
        $arProjectsData[$arProject['ID']] = [
            'NAME' => $arProject['NAME'],
            'URL' => $arProject['DETAIL_PAGE_URL']
        ];

    unset($rsProjects, $arProject);
}

unset($arLink);

if (!empty($arVideoData) || !empty($arServicesData) || !empty($arProjectsData)) {
    $youtube = function ($url) {
        $arrUrl = parse_url($url);

        if (isset($arrUrl['query'])) {
            $arrUrlGet = explode('&', $arrUrl['query']);
            foreach ($arrUrlGet as $value) {
                $arrGetParam = explode('=', $value);
                if (!strcmp(array_shift($arrGetParam), 'v')) {
                    $videoID = array_pop($arrGetParam);
                    break;
                }
            }
            if (empty($videoID)) {
                $videoID = array_pop(explode('/', $arrUrl['path']));
            }
        } else {
            $videoID = array_pop(explode('/', $url));
        }

        return [
            'iframe' => 'https://www.youtube.com/embed/'.$videoID,
            'src' => 'https://www.youtube.com/watch?v='.$videoID,
            'mqdefault' => 'https://img.youtube.com/vi/'.$videoID.'/mqdefault.jpg',
            'hqdefault' => 'https://img.youtube.com/vi/'.$videoID.'/hqdefault.jpg',
            'sddefault' => 'https://img.youtube.com/vi/'.$videoID.'/sddefault.jpg',
            'maxresdefault' => 'https://img.youtube.com/vi/'.$videoID.'/maxresdefault.jpg',
            'id' => $videoID
        ];
    };

    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!empty($arVideoData)) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $arParams['PROPERTY_VIDEO'],
                'VALUE'
            ]);

            if (!empty($arProperty)) {
                if (Type::isArray($arProperty))
                    $arProperty = ArrayHelper::getFirstValue($arProperty);

                if (ArrayHelper::keyExists($arProperty, $arVideoData))
                    $arItem['DATA']['VIDEO'] = $youtube($arVideoData[$arProperty]);
            }
        }

        if (!empty($arServicesData)) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $arParams['PROPERTY_SERVICES'],
                'VALUE'
            ]);

            if (!empty($arProperty)) {
                $arData = [];

                if (Type::isArray($arProperty))
                    foreach ($arProperty as $sProperty)
                        if (ArrayHelper::keyExists($sProperty, $arServicesData))
                            $arData[] = $arServicesData[$sProperty];
                else
                    $arData[] = $arServicesData[$sProperty];

                $arItem['DATA']['SERVICES'] = $arData;

                unset($arData);
            }
        }

        if (!empty($arProjectsData)) {
            $arProperty = ArrayHelper::getValue($arItem, [
                'PROPERTIES',
                $arParams['PROPERTY_PROJECTS'],
                'VALUE'
            ]);

            if (!empty($arProperty)) {
                $arData = [];

                if (Type::isArray($arProperty))
                    foreach ($arProperty as $sProperty)
                        if (ArrayHelper::keyExists($sProperty, $arProjectsData))
                            $arData[] = $arProjectsData[$sProperty];
                else
                    $arData[] = $arProjectsData[$arProperty];

                $arItem['DATA']['PROJECTS'] = $arData;

                unset($arData);
            }
        }
    }

    unset($arItem);
}

unset($arProperty);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'POSITION' => [
        'SHOW' => $arParams['POSITION_SHOW'] === 'Y' && !empty($arParams['PROPERTY_POSITION'])
    ],
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y' && !empty($arVideoData),
        'QUALITY' => ArrayHelper::fromRange([
            'sddefault',
            'mqdefault',
            'hqdefault',
            'maxresdefault'
        ], $arParams['VIDEO_QUALITY'])
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y' && !empty($arServicesData)
    ],
    'PROJECTS' => [
        'SHOW' => $arParams['PROJECTS_SHOW'] === 'Y' && !empty($arProjectsData)
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = ArrayHelper::merge($arResult['VISUAL'], $arVisual);

unset($arVisual, $arVideoData, $arServicesData, $arProjectsData);

$arFooter = [
    'SHOW' => $arParams['FOOTER_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'left',
        'center',
        'right'
    ], $arParams['FOOTER_POSITION']),
    'BUTTON' => [
        'SHOW' => $arParams['FOOTER_BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['FOOTER_BUTTON_TEXT'],
        'LINK' => null
    ]
];

if (!empty($arParams['LIST_PAGE_URL']))
    $arFooter['BUTTON']['LINK'] = StringHelper::replaceMacros(
        $arParams['LIST_PAGE_URL'],
        $arMacros
    );

if (empty($arFooter['BUTTON']['TEXT']) || empty($arFooter['BUTTON']['LINK']))
    $arFooter['BUTTON']['SHOW'] = false;

if (!$arFooter['BUTTON']['SHOW'])
    $arFooter['SHOW'] = false;

$arResult['BLOCKS']['FOOTER'] = $arFooter;

unset($arFooter, $arMacros);