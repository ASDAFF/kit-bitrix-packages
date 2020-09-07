<?php

use intec\core\bitrix\components\IBlockElements;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\net\Url;

class IntecVideoComponent extends IBlockElements
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
            'SECTION' => null,
            'ELEMENTS_MODE' => 'id',
            'ELEMENT' => null,
            'PICTURE_SOURCES' => [],
            'PICTURE_SERVICE_QUALITY' => 'sddefault',
            'PROPERTY_LINK' => null,
            'HEADER_SHOW' => 'N',
            'HEADER_POSITION' => 'center',
            'HEADER_TEXT' => null,
            'DESCRIPTION_SHOW' => 'N',
            'DESCRIPTION_POSITION' => 'center',
            'DESCRIPTION_TEXT' => null,
            'SORT_BY' => 'SORT',
            'ORDER_BY' => 'ASC',
            'FILTER' => []
        ], $arParams);

        if (!Type::isArray($arParams['PICTURE_SOURCES']))
            $arParams['PICTURE_SOURCES'] = [];

        if (!Type::isArray($arParams['FILTER']))
            $arParams['FILTER'] = [];

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
                            'center',
                            'left',
                            'right'
                        ], $arParams['HEADER_POSITION']),
                        'TEXT' => $arParams['~HEADER_TEXT'], ['br']
                    ],
                    'DESCRIPTION' => [
                        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
                        'POSITION' => ArrayHelper::fromRange([
                            'center',
                            'left',
                            'right'
                        ], $arParams['DESCRIPTION_POSITION']),
                        'TEXT' => $arParams['~DESCRIPTION_TEXT'], ['br']
                    ]
                ],
                'VISUAL' => [],
                'LINK' => [],
                'PICTURE' => [],
                'ITEM' => []
            ];

            if (empty($arResult['BLOCKS']['HEADER']['TEXT']))
                $arResult['BLOCKS']['HEADER']['SHOW'] = false;

            if (empty($arResult['BLOCKS']['DESCRIPTION']['TEXT']))
                $arResult['BLOCKS']['DESCRIPTION']['SHOW'] = false;

            $arSort = [];

            if (!empty($arParams['SORT_BY']) && !empty($arParams['ORDER_BY'])) {
                $arSort = [
                    $arParams['SORT_BY'] => $arParams['ORDER_BY']
                ];
            }

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
                $this->setSectionsCode($arParams['SECTION']);
            } else {
                $this->setSectionsId($arParams['SECTION']);
            }

            if ($arParams['ELEMENTS_MODE'] === 'code') {
                $this->setElementsCode($arParams['ELEMENT']);
            } else {
                $this->setElementsId($arParams['ELEMENT']);
            }

            $arItem = $this->getElements($arSort, $arFilter, 1);

            unset($arFilter, $arSort);

            if (!empty($arItem)) {
                $arItem = ArrayHelper::getFirstValue($arItem);

                $arResult['ITEM'] = $arItem;

                $arPictureSources = [
                    'SERVICE' => ArrayHelper::isIn('service', $arParams['PICTURE_SOURCES']),
                    'PREVIEW' => ArrayHelper::isIn('preview', $arParams['PICTURE_SOURCES']),
                    'DETAIL' => ArrayHelper::isIn('detail', $arParams['PICTURE_SOURCES'])
                ];

                if (empty($arParams['PICTURE_SOURCES'])) {
                    $arPictureSources = [
                        'SERVICE' => true,
                        'PREVIEW' => true,
                        'DETAIL' => true
                    ];
                }

                $arService = [];

                if (!empty($arParams['PROPERTY_LINK'])) {
                    $arProperty = ArrayHelper::getValue($arItem, [
                        'PROPERTIES',
                        $arParams['PROPERTY_LINK']
                    ]);

                    if (!empty($arProperty['VALUE'])) {
                        if (Type::isArray($arProperty['VALUE']))
                            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

                        $arService = $this->parseUrl($arProperty['VALUE']);

                        if (!empty($arService)) {
                            $arResult['LINK'] = $arService['LINKS'];
                            $arResult['LINK']['ID'] = $arService['ID'];
                        }
                    }

                    unset($arProperty);
                }

                if ($arPictureSources['DETAIL'] && !empty($arItem['DETAIL_PICTURE'])) {
                    $arResult['PICTURE'] = $arItem['DETAIL_PICTURE'];
                    $arResult['PICTURE']['SOURCE'] = 'detail';
                } else if ($arPictureSources['PREVIEW'] && !empty($arItem['PREVIEW_PICTURE'])) {
                    $arResult['PICTURE'] = $arItem['PREVIEW_PICTURE'];
                    $arResult['PICTURE']['SOURCE'] = 'preview';
                } else if ($arPictureSources['SERVICE'] && !empty($arService)) {
                    $arResult['PICTURE'] = [
                        'SRC' => $arService['PICTURES'][$arParams['PICTURE_SERVICE_QUALITY']],
                        'SOURCE' => 'service'
                    ];
                } else {
                    $arResult['PICTURE'] = [
                        'SRC' => null,
                        'SOURCE' => 'none'
                    ];
                }

                unset($arPictureSources);
            }

            $this->arResult = $arResult;

            unset($arResult, $arParams, $arItem, $arService);

            $this->includeComponentTemplate();
        }

        return null;
    }
}