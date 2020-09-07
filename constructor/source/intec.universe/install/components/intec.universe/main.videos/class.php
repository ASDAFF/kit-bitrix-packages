<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\net\Url;

class IntecVideosComponent extends IBlockElements
{
    public function parseUrl($url) {
        $url = new Url($url);
        $video = null;

        if ($url->getQuery()->exists('v'))
            $video = $url->getQuery()->get('v');

        if (empty($video))
            $video = $url->getPath()->getLast();

        if (empty($video))
            return null;

        return [
            'ID' => $video,
            'LINKS' => [
                'embed' => 'https://www.youtube.com/embed/'.$video,
                'page' => 'https://www.youtube.com/watch?v='.$video
            ],
            'PICTURES' => [
                'mqdefault' => 'https://img.youtube.com/vi/'.$video.'/mqdefault.jpg',
                'hqdefault' => 'https://img.youtube.com/vi/'.$video.'/hqdefault.jpg',
                'sddefault' => 'https://img.youtube.com/vi/'.$video.'/sddefault.jpg',
                'maxresdefault' => 'https://img.youtube.com/vi/'.$video.'/maxresdefault.jpg'
            ]
        ];
    }

    public function onPrepareComponentParams($arParams) {
        if (!Type::isArray($arParams))
            $arParams = [];

        $arParams = ArrayHelper::merge([
            'IBLOCK_TYPE' => null,
            'IBLOCK_ID' => null,
            'SECTIONS_MODE' => 'id',
            'SECTIONS' => [],
            'PROPERTY_URL' => null,
            'HEADER' => null,
            '~HEADER' => null,
            'HEADER_SHOW' => 'N',
            'HEADER_POSITION' => 'center',
            'DESCRIPTION' => null,
            '~DESCRIPTION' => null,
            'DESCRIPTION_SHOW' => 'N',
            'DESCRIPTION_POSITION' => 'center',
            'ELEMENTS_COUNT' => null,
            'PICTURE_SOURCES' => [],
            'PICTURE_SERVICE_QUALITY' => 'sddefault',
            'SORT_BY' => 'sort',
            'ORDER_BY' => 'asc',
            'FILTER' => []
        ], $arParams);

        if (!Type::isArray($arParams['SECTIONS']))
            $arParams['SECTIONS'] = [];

        if (!Type::isArray($arParams['PICTURE_SOURCE']))
            $arParams['PICTURE_SOURCE'] = [];

        if (!Type::isArray($arParams['FILTER']))
            $arParams['FILTER'];

        return $arParams;
    }

    public function executeComponent() {
        global $USER;

        if ($this->startResultCache(false, $USER->GetGroups())) {
            $arParams = $this->arParams;
            $arResult = [
                'BLOCKS' => [
                    'HEADER' => [
                        'SHOW' => $arParams['HEADER_SHOW'] === 'Y',
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['HEADER_POSITION']),
                        'TEXT' => $arParams['~HEADER']
                    ],
                    'DESCRIPTION' => [
                        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
                        'POSITION' => ArrayHelper::fromRange([
                            'left',
                            'center',
                            'right'
                        ], $arParams['DESCRIPTION_POSITION']),
                        'TEXT' => $arParams['~DESCRIPTION']
                    ]
                ],
                'VISUAL' => [],
                'ITEMS' => []
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            $arSort = [];

            if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY']))
                $arSort = [
                    $arParams['SORT_BY'] => $arParams['ORDER_BY']
                ];

            $arFilter = ArrayHelper::merge([
                'IBLOCK_LID' => $this->getSiteId(),
                'ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y',
                'MIN_PERMISSION' => 'R'
            ], $arParams['FILTER']);

            $this->setIBlockType($arParams['IBLOCK_TYPE']);
            $this->setIBlockId($arParams['IBLOCK_ID']);

            if ($arParams['SECTIONS_MODE'] === 'code') {
                $this->setSectionsCode($arParams['SECTIONS']);
            } else {
                $this->setSectionsId($arParams['SECTIONS']);
            }

            $arItems = $this->getElements(
                $arSort,
                $arFilter,
                $arParams['ELEMENTS_COUNT']
            );

            unset($arSort, $arFilter);

            if (!empty($arItems)) {
                if (!empty($arParams['PICTURE_SOURCES'])) {
                    $arPictureSources = [
                        'SERVICE' => ArrayHelper::isIn('service', $arParams['PICTURE_SOURCES']),
                        'PREVIEW' => ArrayHelper::isIn('preview', $arParams['PICTURE_SOURCES']),
                        'DETAIL' => ArrayHelper::isIn('detail', $arParams['PICTURE_SOURCES'])
                    ];
                } else {
                    $arPictureSources = [
                        'SERVICE' => true,
                        'PREVIEW' => true,
                        'DETAIL' => true
                    ];
                }

                $sServiceQuality = ArrayHelper::fromRange([
                    'mqdefault',
                    'hqdefault',
                    'sddefault',
                    'maxresdefault'
                ], $arParams['PICTURE_SERVICE_QUALITY']);

                foreach ($arItems as &$arItem) {
                    $arData = [
                        'URL' => null,
                        'PICTURE' => []
                    ];

                    $arService = [];

                    if (!empty($arParams['PROPERTY_URL'])) {
                        $arProperty = ArrayHelper::getValue($arItem, [
                            'PROPERTIES',
                            $arParams['PROPERTY_URL']
                        ]);

                        if (!empty($arProperty['VALUE'])) {
                            if (Type::isArray($arProperty['VALUE']))
                                $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

                            $arService = $this->parseUrl($arProperty['VALUE']);

                            if (!empty($arService)) {
                                $arData['URL'] = $arService['LINKS'];
                                $arData['URL']['ID'] = $arService['ID'];
                            }
                        }

                        unset($arProperty);
                    }

                    if ($arPictureSources['DETAIL'] && !empty($arItem['DETAIL_PICTURE'])) {
                        $arData['PICTURE'] = $arItem['DETAIL_PICTURE'];
                        $arData['PICTURE']['SOURCE'] = 'detail';
                    } else if ($arPictureSources['PREVIEW'] && !empty($arItem['PREVIEW_PICTURE'])) {
                        $arData['PICTURE'] = $arItem['PREVIEW_PICTURE'];
                        $arData['PICTURE']['SOURCE'] = 'preview';
                    } else if ($arPictureSources['SERVICE'] && !empty($arService)) {
                        $arData['PICTURE'] = [
                            'SRC' => $arService['PICTURES'][$sServiceQuality],
                            'SOURCE' => 'service'
                        ];
                    } else {
                        $arData['PICTURE'] = [
                            'SRC' => null,
                            'SOURCE' => 'none'
                        ];
                    }

                    $arItem['URL'] = $arData['URL'];
                    $arItem['PICTURE'] = $arData['PICTURE'];

                    unset($arData, $arService);
                }

                unset($arPictureSources, $sServiceQuality, $arItem);

                $arResult['ITEMS'] = $arItems;
            }

            $this->arResult = $arResult;

            unset($arResult);

            $this->includeComponentTemplate();
        }

        return null;
    }
}