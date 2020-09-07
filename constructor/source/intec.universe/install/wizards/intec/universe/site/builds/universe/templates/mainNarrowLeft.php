<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var Closure $templateWidgetsLoad
 */
include(__DIR__.'/../.begin.php');

return [
    'code' => 'mainNarrowLeft',
    'name' => 'Главная (C левой колонкой)',
    'default' => 1,
    'sort' => 300,
    'condition' => [
        'type' => 'group',
        'operator' => 'and',
        'result' => 1,
        'conditions' => [[
            'type' => 'parameter.page',
            'key' => 'pages-main-template',
            'logic' => '=',
            'value' => 'narrow.left',
            'result' => 1
        ]]
    ],
    'container' => [
        'code' => 'template',
        'properties' => [
            'class' => 'intec-template'
        ],
        'containers' => [[
            'code' => 'template-content',
            'properties' => [
                'class' => 'intec-template-content'
            ],
            'containers' => [[
                'code' => 'template-content-wrapper',
                'properties' => [
                    'class' => 'intec-template-content-wrapper'
                ],
                'containers' => ArrayHelper::merge([[
                    'code' => 'template-header',
                    'order' => 0,
                    'properties' => [
                        'class' => 'intec-template-header',
                        'background' => [
                            'color' => '#f8f9fb'
                        ]
                    ],
                    'area' => 'header'
                ], [
                    'code' => 'template-page',
                    'order' => 1,
                    'properties' => [
                        'class' => 'intec-template-page intec-content intec-content-visible'
                    ],
                    'containers' => [[
                        'code' => 'template-page-wrapper',
                        'properties' => [
                            'class' => 'intec-template-page-wrapper intec-content-wrapper'
                        ],
                        'containers' => [[
                            'properties' => [
                                'class' => 'intec-content-left'
                            ],
                            'containers' => [[
                                'component' => [
                                    'code' => 'bitrix:menu',
                                    'template' => 'vertical.1',
                                    'properties' => [
                                        'ROOT_MENU_TYPE' => 'catalog',
                                        'IBLOCK_TYPE' => '#PRODUCTS_IBLOCK_TYPE#',
                                        'IBLOCK_ID' => '#PRODUCTS_IBLOCK_ID#',
                                        'PROPERTY_IMAGE' => 'UF_IMAGE',
                                        'MENU_CACHE_TYPE' => 'N',
                                        'MENU_CACHE_TIME' => 3600000,
                                        'MENU_CACHE_USE_GROUPS' => 'N',
                                        'MENU_CACHE_GET_VARS' => [],
                                        'MAX_LEVEL' => '4',
                                        'CHILD_MENU_TYPE' => 'catalog',
                                        'USE_EXT' => 'Y',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'N'
                                    ]
                                ]
                            ], [
                                'code' => 'pages-main-blocks.news',
                                'properties' => [
                                    'margin' => [
                                        'top' => ['value' => 50, 'measure' => 'px']
                                    ]
                                ],
                                'component' => [
                                    'code' => 'intec.universe:main.news',
                                    'template' => 'template.4',
                                    'properties' => [
                                        'IBLOCK_TYPE' => '#CONTENT_NEWS_IBLOCK_TYPE#',
                                        'IBLOCK_ID' => '#CONTENT_NEWS_IBLOCK_ID#',
                                        'ELEMENTS_COUNT' => 4,
                                        'HEADER_BLOCK_SHOW' => 'Y',
                                        'HEADER_BLOCK_POSITION' => 'center',
                                        'HEADER_BLOCK_TEXT' => 'Новости',
                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                        'LINK_USE' => 'Y',
                                        'DATE_SHOW' => 'Y',
                                        'DATE_FORMAT' => 'd.m.Y',
                                        'SEE_ALL_SHOW' => 'N',
                                        'SECTION_URL' => '',
                                        'DETAIL_URL' => '',
                                        'CACHE_TYPE' => 'A',
                                        'CACHE_TIME' => 3600000,
                                        'SORT_BY' => 'DATE_ACTIVE',
                                        'ORDER_BY' => 'DESC'
                                    ]
                                ]
                            ]]
                        ], [
                            'properties' => [
                                'class' => 'intec-content-right'
                            ],
                            'containers' => [[
                                'code' => 'template-page-content',
                                'properties' => [
                                    'class' => 'intec-template-page-content intec-content-right-wrapper'
                                ],
                                'containers' => $templateBlocksLoad('pages-main-blocks.', [
                                    'banner' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            '2' => [
                                                'name' => 'Тип 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.slider',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '',
                                                        'SECTIONS_MODE' => 'id',
                                                        'LAZYLOAD_USE' => 'Y',
                                                        'BLOCKS_USE' => 'Y',
                                                        'BLOCKS_IBLOCK_TYPE' => '#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#',
                                                        'BLOCKS_IBLOCK_ID' => '#CONTENT_BANNERS_SMALL_IBLOCK_ID#',
                                                        'BLOCKS_MODE' => 'N',
                                                        'BLOCKS_ELEMENTS_COUNT' => '4',
                                                        'PROPERTY_HEADER' => 'TITLE',
                                                        'PROPERTY_DESCRIPTION' => 'DESCRIPTION',
                                                        'PROPERTY_HEADER_OVER' => 'HEADER_OVER',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'PROPERTY_BUTTON_SHOW' => 'BUTTON_SHOW',
                                                        'PROPERTY_BUTTON_TEXT' => 'BUTTON_TEXT',
                                                        'PROPERTY_TEXT_ALIGN' => 'TEXT_ALIGN',
                                                        'PROPERTY_FADE' => 'BACKGROUND_FADE',
                                                        'PROPERTY_SCHEME' => 'TEXT_DARK',
                                                        'PROPERTY_VIDEO' => 'BACKGROUND_VIDEO',
                                                        'PROPERTY_VIDEO_FILE_MP4' => 'BACKGROUND_VIDEO_FILE_MP4',
                                                        'PROPERTY_VIDEO_FILE_WEBM' => 'BACKGROUND_VIDEO_FILE_WEBM',
                                                        'PROPERTY_VIDEO_FILE_OGV' => 'BACKGROUND_VIDEO_FILE_OGV',
                                                        'BLOCKS_PROPERTY_LINK' => 'LINK',
                                                        'BLOCKS_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'HEIGHT' => '450px',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_VIEW' => '1',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_VIEW' => '1',
                                                        'HEADER_OVER_SHOW' => 'Y',
                                                        'HEADER_OVER_VIEW' => '1',
                                                        'BUTTON_VIEW' => '1',
                                                        'VIDEO_SHOW' => 'Y',
                                                        'BLOCKS_POSITION' => 'both',
                                                        'BLOCKS_EFFECT_FADE' => 'Y',
                                                        'BLOCKS_EFFECT_SCALE' => 'Y',
                                                        'BLOCKS_INDENT' => 'Y',
                                                        'ROUNDED' => 'Y',
                                                        'SLIDER_NAV_SHOW' => 'Y',
                                                        'SLIDER_NAV_VIEW' => '1',
                                                        'SLIDER_DOTS_SHOW' => 'Y',
                                                        'SLIDER_DOTS_VIEW' => '1',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => '3600000',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            '3' => [
                                                'name' => 'Тип 3',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.slider',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '',
                                                        'SECTIONS_MODE' => 'id',
                                                        'LAZYLOAD_USE' => 'Y',
                                                        'BLOCKS_USE' => 'Y',
                                                        'BLOCKS_IBLOCK_TYPE' => '#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#',
                                                        'BLOCKS_IBLOCK_ID' => '#CONTENT_BANNERS_SMALL_IBLOCK_ID#',
                                                        'BLOCKS_MODE' => 'N',
                                                        'BLOCKS_ELEMENTS_COUNT' => '4',
                                                        'PROPERTY_HEADER' => 'TITLE',
                                                        'PROPERTY_DESCRIPTION' => 'DESCRIPTION',
                                                        'PROPERTY_HEADER_OVER' => 'HEADER_OVER',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'PROPERTY_BUTTON_SHOW' => 'BUTTON_SHOW',
                                                        'PROPERTY_BUTTON_TEXT' => 'BUTTON_TEXT',
                                                        'PROPERTY_TEXT_ALIGN' => 'TEXT_ALIGN',
                                                        'PROPERTY_FADE' => 'BACKGROUND_FADE',
                                                        'PROPERTY_SCHEME' => 'TEXT_DARK',
                                                        'PROPERTY_VIDEO' => 'BACKGROUND_VIDEO',
                                                        'PROPERTY_VIDEO_FILE_MP4' => 'BACKGROUND_VIDEO_FILE_MP4',
                                                        'PROPERTY_VIDEO_FILE_WEBM' => 'BACKGROUND_VIDEO_FILE_WEBM',
                                                        'PROPERTY_VIDEO_FILE_OGV' => 'BACKGROUND_VIDEO_FILE_OGV',
                                                        'BLOCKS_PROPERTY_LINK' => 'LINK',
                                                        'BLOCKS_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'HEIGHT' => '450px',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_VIEW' => '1',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_VIEW' => '1',
                                                        'HEADER_OVER_SHOW' => 'Y',
                                                        'HEADER_OVER_VIEW' => '1',
                                                        'BUTTON_VIEW' => '1',
                                                        'VIDEO_SHOW' => 'Y',
                                                        'BLOCKS_POSITION' => 'right',
                                                        'BLOCKS_EFFECT_FADE' => 'Y',
                                                        'BLOCKS_EFFECT_SCALE' => 'Y',
                                                        'BLOCKS_INDENT' => 'Y',
                                                        'ROUNDED' => 'Y',
                                                        'SLIDER_NAV_SHOW' => 'Y',
                                                        'SLIDER_NAV_VIEW' => '1',
                                                        'SLIDER_DOTS_SHOW' => 'Y',
                                                        'SLIDER_DOTS_VIEW' => '1',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => '3600000',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            '4' => [
                                                'name' => 'Тип 4',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.slider',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '',
                                                        'SECTIONS_MODE' => 'id',
                                                        'LAZYLOAD_USE' => 'Y',
                                                        'BLOCKS_USE' => 'Y',
                                                        'BLOCKS_IBLOCK_TYPE' => '#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#',
                                                        'BLOCKS_IBLOCK_ID' => '#CONTENT_BANNERS_SMALL_IBLOCK_ID#',
                                                        'BLOCKS_MODE' => 'N',
                                                        'BLOCKS_ELEMENTS_COUNT' => '2',
                                                        'PROPERTY_HEADER' => 'TITLE',
                                                        'PROPERTY_DESCRIPTION' => 'DESCRIPTION',
                                                        'PROPERTY_HEADER_OVER' => 'HEADER_OVER',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'PROPERTY_BUTTON_SHOW' => 'BUTTON_SHOW',
                                                        'PROPERTY_BUTTON_TEXT' => 'BUTTON_TEXT',
                                                        'PROPERTY_TEXT_ALIGN' => 'TEXT_ALIGN',
                                                        'PROPERTY_FADE' => 'BACKGROUND_FADE',
                                                        'PROPERTY_SCHEME' => 'TEXT_DARK',
                                                        'PROPERTY_VIDEO' => 'BACKGROUND_VIDEO',
                                                        'PROPERTY_VIDEO_FILE_MP4' => 'BACKGROUND_VIDEO_FILE_MP4',
                                                        'PROPERTY_VIDEO_FILE_WEBM' => 'BACKGROUND_VIDEO_FILE_WEBM',
                                                        'PROPERTY_VIDEO_FILE_OGV' => 'BACKGROUND_VIDEO_FILE_OGV',
                                                        'BLOCKS_PROPERTY_LINK' => 'LINK',
                                                        'BLOCKS_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'HEIGHT' => '450px',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_VIEW' => '1',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_VIEW' => '1',
                                                        'HEADER_OVER_SHOW' => 'Y',
                                                        'HEADER_OVER_VIEW' => '1',
                                                        'BUTTON_VIEW' => '1',
                                                        'VIDEO_SHOW' => 'Y',
                                                        'BLOCKS_POSITION' => 'right',
                                                        'BLOCKS_EFFECT_FADE' => 'Y',
                                                        'BLOCKS_EFFECT_SCALE' => 'Y',
                                                        'BLOCKS_INDENT' => 'Y',
                                                        'ROUNDED' => 'Y',
                                                        'SLIDER_NAV_SHOW' => 'Y',
                                                        'SLIDER_NAV_VIEW' => '1',
                                                        'SLIDER_DOTS_SHOW' => 'Y',
                                                        'SLIDER_DOTS_VIEW' => '1',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => '3600000',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            '10' => [
                                                'name' => 'Тип 10',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.slider',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '',
                                                        'SECTIONS_MODE' => 'id',
                                                        'LAZYLOAD_USE' => 'Y',
                                                        'BLOCKS_USE' => 'Y',
                                                        'BLOCKS_IBLOCK_TYPE' => '#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#',
                                                        'BLOCKS_IBLOCK_ID' => '#CONTENT_BANNERS_SMALL_IBLOCK_ID#',
                                                        'BLOCKS_MODE' => 'N',
                                                        'BLOCKS_ELEMENTS_COUNT' => '2',
                                                        'PROPERTY_HEADER' => 'TITLE',
                                                        'PROPERTY_DESCRIPTION' => 'DESCRIPTION',
                                                        'PROPERTY_HEADER_OVER' => 'HEADER_OVER',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'PROPERTY_BUTTON_SHOW' => 'BUTTON_SHOW',
                                                        'PROPERTY_BUTTON_TEXT' => 'BUTTON_TEXT',
                                                        'PROPERTY_TEXT_ALIGN' => 'TEXT_ALIGN',
                                                        'PROPERTY_FADE' => 'BACKGROUND_FADE',
                                                        'PROPERTY_SCHEME' => 'TEXT_DARK',
                                                        'PROPERTY_VIDEO' => 'BACKGROUND_VIDEO',
                                                        'PROPERTY_VIDEO_FILE_MP4' => 'BACKGROUND_VIDEO_FILE_MP4',
                                                        'PROPERTY_VIDEO_FILE_WEBM' => 'BACKGROUND_VIDEO_FILE_WEBM',
                                                        'PROPERTY_VIDEO_FILE_OGV' => 'BACKGROUND_VIDEO_FILE_OGV',
                                                        'BLOCKS_PROPERTY_LINK' => 'LINK',
                                                        'BLOCKS_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                                                        'WIDE' => 'N',
                                                        'HEIGHT' => '450px',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_VIEW' => '1',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_VIEW' => '1',
                                                        'HEADER_OVER_SHOW' => 'Y',
                                                        'HEADER_OVER_VIEW' => '1',
                                                        'BUTTON_VIEW' => '1',
                                                        'VIDEO_SHOW' => 'Y',
                                                        'BLOCKS_POSITION' => 'right',
                                                        'BLOCKS_EFFECT_FADE' => 'Y',
                                                        'BLOCKS_EFFECT_SCALE' => 'Y',
                                                        'BLOCKS_INDENT' => 'Y',
                                                        'ROUNDED' => 'Y',
                                                        'SLIDER_NAV_SHOW' => 'Y',
                                                        'SLIDER_NAV_VIEW' => '1',
                                                        'SLIDER_DOTS_SHOW' => 'Y',
                                                        'SLIDER_DOTS_VIEW' => '1',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => '3600000',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'icons' => [
                                        'type' => 'simple',
                                        'properties' => [
                                            'margin' => [
                                                'bottom' => ['value' => 50, 'measure' => 'px']
                                            ]
                                        ],
                                        'widget' => [
                                            'code' => 'intec.constructor:icons',
                                            'template' => 'intec.both',
                                            'properties' => [
                                                'header' => [
                                                    'show' => ''
                                                ],
                                                'caption' => [
                                                    'style' => [
                                                        'bold' => '',
                                                        'italic' => '',
                                                        'underline' => ''
                                                    ],
                                                    'text' => [
                                                        'align' => [
                                                            'value' => 'left'
                                                        ],
                                                        'size' => [
                                                            'value' => 14,
                                                            'measure' => 'px'
                                                        ],
                                                        'color' => '#000000',
                                                    ],
                                                    'opacity' => 0
                                                ],
                                                'description' => [
                                                    'style' => [
                                                        'bold' => '',
                                                        'italic' => '',
                                                        'underline' => ''
                                                    ],
                                                    'text' => [
                                                        'align' => [
                                                            'value' => 'center'
                                                        ],
                                                        'size' => [
                                                            'value' => 14,
                                                            'measure' => 'px'
                                                        ]
                                                    ],
                                                    'opacity' => 0,
                                                ],
                                                'background' => [
                                                    'show' => '',
                                                    'color' => '#f0f0f0',
                                                    'rounding' => [
                                                        'value' => 100,
                                                        'measure' => 'px',
                                                        'shared' => '',
                                                        'top' => [
                                                            'value' => null,
                                                            'measure' => 'px'
                                                        ],
                                                        'right' => [
                                                            'value' => null,
                                                            'measure' => 'px'
                                                        ],
                                                        'bottom' => [
                                                            'value' => null,
                                                            'measure' => 'px'
                                                        ],
                                                        'left' => [
                                                            'value' => null,
                                                            'measure' => 'px'
                                                        ]
                                                    ],
                                                    'opacity' => 0
                                                ],
                                                'items' => [[
                                                    'name' => 'Акции и скидки для постоянных клиентов',
                                                    'image' => '#TEMPLATE#/images/gallery/1253846-200.png'
                                                ], [
                                                    'name' => 'Качественные услуги и сервис',
                                                    'image' => '#TEMPLATE#/images/gallery/842988-200.png'
                                                ], [
                                                    'name' => 'Широкий ассортимент товаров',
                                                    'image' => '#TEMPLATE#/images/gallery/1272607-200.png'
                                                ]],
                                                'count' => 3
                                            ]
                                        ]
                                    ],
                                    'sections' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'list.1' => [
                                                'name' => 'Список 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.sections',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'QUANTITY' => 'N',
                                                        'SECTIONS_MODE' => 'id',
                                                        'DEPTH' => 2,
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Популярные категории',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 2,
                                                        'SUB_SECTIONS_SHOW' => 'Y',
                                                        'SUB_SECTIONS_MAX' => 3,
                                                        'SECTION_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.sections',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'QUANTITY' => 'N',
                                                        'SECTIONS_MODE' => 'id',
                                                        'DEPTH' => 1,
                                                        'ELEMENTS_COUNT' => '6',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Популярные категории',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'SECTION_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'categories' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'chess.1' => [
                                                'name' => 'Шахматка 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.6',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '6',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_STICKER' => 'STICKER_TEXT',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'VIEW_STYLE' => 'chess',
                                                        'NAME_HORIZONTAL' => 'center',
                                                        'NAME_VERTICAL' => 'middle',
                                                        'STICKER_SHOW' => 'Y',
                                                        'STICKER_HORIZONTAL' => 'left',
                                                        'STICKER_VERTICAL' => 'top',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'mosaic.1' => [
                                                'name' => 'Мозайка 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.8',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '5',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_STICKER' => 'STICKER_TEXT',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'FIRST_ITEM_BIG' => 'Y',
                                                        'NAME_HORIZONTAL' => 'left',
                                                        'NAME_VERTICAL' => 'bottom',
                                                        'STICKER_SHOW' => 'Y',
                                                        'STICKER_HORIZONTAL' => 'left',
                                                        'STICKER_VERTICAL' => 'top',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.7',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_STICKER' => 'STICKER_TEXT',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'NAME_HORIZONTAL' => 'center',
                                                        'NAME_VERTICAL' => 'middle',
                                                        'STICKER_SHOW' => 'N',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'list.1' => [
                                                'name' => 'Список 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'PICTURE_SHOW' => 'Y',
                                                        'PREVIEW_SHOW' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.3' => [
                                                'name' => 'Плитки 3',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '6',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.4' => [
                                                'name' => 'Плитки 4',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.4',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'PREVIEW_SHOW' => 'Y',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.5' => [
                                                'name' => 'Плитки 5',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.5',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '6',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_SIZE' => 'SIZE',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'POSITION_HORIZONTAL' => 'left',
                                                        'POSITION_VERTICAL' => 'bottom',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'chess.2' => [
                                                'name' => 'Шахматка 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.6',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'PROPERTY_STICKER' => 'STICKER_TEXT',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Рубрики',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'VIEW_STYLE' => 'chess',
                                                        'NAME_HORIZONTAL' => 'center',
                                                        'NAME_VERTICAL' => 'top',
                                                        'STICKER_SHOW' => 'Y',
                                                        'STICKER_HORIZONTAL' => 'left',
                                                        'STICKER_VERTICAL' => 'top',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.6' => [
                                                'name' => 'Плитки 6',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.categories',
                                                    'template' => 'template.13',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BANNERS_CATEGORIES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'LINK_MODE' => 'property',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'N',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'WIDE' => 'Y',
                                                        'PREVIEW_SHOW' => 'Y',
                                                        'LINK_USE' => 'Y',
                                                        'LINK_BLANK' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'SORT_ORDER' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'gallery' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:widget',
                                                    'template' => 'photo',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_PHOTO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_PHOTO_IBLOCK_ID#',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'SHOW_TITLE' => 'Y',
                                                        'TITLE' => 'Фотогалерея',
                                                        'ALIGHT_HEADER' => 'N',
                                                        'SHOW_DETAIL_LINK' => 'N',
                                                        'USE_CAROUSEL' => 'N',
                                                        'COLUMNS_COUNT' => 4,
                                                        'ITEMS_LIMIT' => 8,
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.gallery',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_PHOTO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_PHOTO_IBLOCK_ID#',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Фотогалерея',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 4,
                                                        'ALIGNMENT' => 'center',
                                                        'TABS_POSITION' => 'center',
                                                        'DELIMITERS' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.3' => [
                                                'name' => 'Плитки 3 (Широкие)',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.gallery',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_PHOTO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_PHOTO_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '8',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Фотогалерея',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 4,
                                                        'ALIGNMENT' => 'center',
                                                        'DELIMITERS' => 'N',
                                                        'WIDE' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'products' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'slider.1' => [
                                                'name' => 'Слайдер 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.widget',
                                                    'template' => 'products.1',
                                                    'properties' => [
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'MODE' => 'all',
                                                        'DELAY_USE' => 'Y',
                                                        'FORM_ID' => '#FORMS_PRODUCT_ID#',
                                                        'FORM_PROPERTY_PRODUCT' => '#FORMS_PRODUCT_FIELDS_PRODUCT_ID#',
                                                        'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                                        'QUICK_VIEW_USE' => 'Y',
                                                        'QUICK_VIEW_DETAIL' => 'N',
                                                        'QUICK_VIEW_TEMPLATE' => 2,
                                                        'QUICK_VIEW_PROPERTY_CODE' => [
                                                            'PROPERTY_TYPE',
                                                            'PROPERTY_QUANTITY_OF_STRIPS',
                                                            'PROPERTY_POWER',
                                                            'PROPERTY_PROCREATOR',
                                                            'PROPERTY_SCOPE',
                                                            'PROPERTY_DISPLAY',
                                                            'PROPERTY_WEIGTH',
                                                            'PROPERTY_ENERGY_CONSUMPTION',
                                                            'PROPERTY_SETTINGS',
                                                            'PROPERTY_COMPOSITION',
                                                            'PROPERTY_LENGTH',
                                                            'PROPERTY_SEASON',
                                                            'PROPERTY_PATTERN'
                                                        ],
                                                        'QUICK_VIEW_PROPERTY_MARKS_HIT' => 'HIT',
                                                        'QUICK_VIEW_PROPERTY_MARKS_NEW' => 'NEW',
                                                        'QUICK_VIEW_PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'QUICK_VIEW_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_DELAY_USE' => 'Y',
                                                        'QUICK_VIEW_MARKS_SHOW' => 'Y',
                                                        'QUICK_VIEW_MARKS_ORIENTATION' => 'horizontal',
                                                        'QUICK_VIEW_GALLERY_PREVIEW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_SHOW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_MODE' => 'number',
                                                        'QUICK_VIEW_ACTION' => 'buy',
                                                        'QUICK_VIEW_COUNTER_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_MODE' => 'preview',
                                                        'QUICK_VIEW_PROPERTIES_SHOW' => 'Y',
                                                        'QUICK_VIEW_DETAIL_SHOW' => 'Y',
                                                        'PROPERTY_ORDER_USE' => 'ORDER_USE',
                                                        'PROPERTY_MARKS_HIT' => 'HIT',
                                                        'PROPERTY_MARKS_NEW' => 'NEW',
                                                        'PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'PROPERTY_PICTURES' => 'PICTURES',
                                                        'PROPERTY_CATEGORY' => 'CATEGORY',
                                                        'COMPARE_PATH' => '#SITE_DIR#catalog/compare.php',
                                                        'COMPARE_NAME' => 'compare',
                                                        'COLUMNS' => 3,
                                                        'BLOCKS_HEADER_SHOW' => 'Y',
                                                        'BLOCKS_HEADER_TEXT' => 'Товары',
                                                        'BLOCKS_HEADER_ALIGN' => 'left',
                                                        'BLOCKS_DESCRIPTION_SHOW' => 'N',
                                                        'TABS_ALIGN' => 'left',
                                                        'MARKS_SHOW' => 'Y',
                                                        'MARKS_ORIENTATION' => 'horizontal',
                                                        'NAME_ALIGN' => 'left',
                                                        'SECTION_SHOW' => 'Y',
                                                        'SECTION_ALIGN' => 'left',
                                                        'PRICE_ALIGN' => 'start',
                                                        'IMAGE_SLIDER_SHOW' => 'Y',
                                                        'ACTION' => 'buy',
                                                        'VOTE_SHOW' => 'Y',
                                                        'VOTE_ALIGN' => 'left',
                                                        'VOTE_MODE' => 'rating',
                                                        'QUANTITY_SHOW' => 'Y',
                                                        'QUANTITY_MODE' => 'number',
                                                        'QUANTITY_ALIGN' => 'left',
                                                        'USE_COMPARE' => 'Y',
                                                        'SLIDER_USE' => 'Y',
                                                        'SLIDER_NAVIGATION' => 'Y',
                                                        'SLIDER_DOTS' => 'Y',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'PRICE_CODE' => [
                                                            'BASE'
                                                        ],
                                                        'CONVERT_CURRENCY' => 'N',
                                                        'USE_PRICE_COUNT' => 'N',
                                                        'PRICE_VAT_INCLUDE' => 'N'
                                                    ]
                                                ]
                                            ],
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.widget',
                                                    'template' => 'products.1',
                                                    'properties' => [
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'MODE' => 'all',
                                                        'DELAY_USE' => 'Y',
                                                        'FORM_ID' => '#FORMS_PRODUCT_ID#',
                                                        'FORM_PROPERTY_PRODUCT' => '#FORMS_PRODUCT_FIELDS_PRODUCT_ID#',
                                                        'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                                        'QUICK_VIEW_USE' => 'Y',
                                                        'QUICK_VIEW_DETAIL' => 'N',
                                                        'QUICK_VIEW_TEMPLATE' => 2,
                                                        'QUICK_VIEW_PROPERTY_CODE' => [
                                                            'PROPERTY_TYPE',
                                                            'PROPERTY_QUANTITY_OF_STRIPS',
                                                            'PROPERTY_POWER',
                                                            'PROPERTY_PROCREATOR',
                                                            'PROPERTY_SCOPE',
                                                            'PROPERTY_DISPLAY',
                                                            'PROPERTY_WEIGTH',
                                                            'PROPERTY_ENERGY_CONSUMPTION',
                                                            'PROPERTY_SETTINGS',
                                                            'PROPERTY_COMPOSITION',
                                                            'PROPERTY_LENGTH',
                                                            'PROPERTY_SEASON',
                                                            'PROPERTY_PATTERN'
                                                        ],
                                                        'QUICK_VIEW_PROPERTY_MARKS_HIT' => 'HIT',
                                                        'QUICK_VIEW_PROPERTY_MARKS_NEW' => 'NEW',
                                                        'QUICK_VIEW_PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'QUICK_VIEW_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_DELAY_USE' => 'Y',
                                                        'QUICK_VIEW_MARKS_SHOW' => 'Y',
                                                        'QUICK_VIEW_MARKS_ORIENTATION' => 'horizontal',
                                                        'QUICK_VIEW_GALLERY_PREVIEW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_SHOW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_MODE' => 'number',
                                                        'QUICK_VIEW_ACTION' => 'buy',
                                                        'QUICK_VIEW_COUNTER_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_MODE' => 'preview',
                                                        'QUICK_VIEW_PROPERTIES_SHOW' => 'Y',
                                                        'QUICK_VIEW_DETAIL_SHOW' => 'Y',
                                                        'PROPERTY_ORDER_USE' => 'ORDER_USE',
                                                        'PROPERTY_MARKS_HIT' => 'HIT',
                                                        'PROPERTY_MARKS_NEW' => 'NEW',
                                                        'PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'PROPERTY_PICTURES' => 'PICTURES',
                                                        'PROPERTY_CATEGORY' => 'CATEGORY',
                                                        'COMPARE_PATH' => '#SITE_DIR#catalog/compare.php',
                                                        'COMPARE_NAME' => 'compare',
                                                        'COLUMNS' => 3,
                                                        'BLOCKS_HEADER_SHOW' => 'Y',
                                                        'BLOCKS_HEADER_TEXT' => 'Товары',
                                                        'BLOCKS_HEADER_ALIGN' => 'left',
                                                        'BLOCKS_DESCRIPTION_SHOW' => 'N',
                                                        'TABS_ALIGN' => 'left',
                                                        'MARKS_SHOW' => 'Y',
                                                        'MARKS_ORIENTATION' => 'horizontal',
                                                        'NAME_ALIGN' => 'left',
                                                        'SECTION_SHOW' => 'Y',
                                                        'SECTION_ALIGN' => 'left',
                                                        'PRICE_ALIGN' => 'start',
                                                        'IMAGE_SLIDER_SHOW' => 'Y',
                                                        'ACTION' => 'buy',
                                                        'VOTE_SHOW' => 'Y',
                                                        'VOTE_ALIGN' => 'left',
                                                        'VOTE_MODE' => 'rating',
                                                        'QUANTITY_SHOW' => 'Y',
                                                        'QUANTITY_MODE' => 'number',
                                                        'QUANTITY_ALIGN' => 'left',
                                                        'USE_COMPARE' => 'Y',
                                                        'SLIDER_USE' => 'N',
                                                        'SLIDER_NAVIGATION' => 'Y',
                                                        'SLIDER_DOTS' => 'Y',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'PRICE_CODE' => [
                                                            'BASE'
                                                        ],
                                                        'CONVERT_CURRENCY' => 'N',
                                                        'USE_PRICE_COUNT' => 'N',
                                                        'PRICE_VAT_INCLUDE' => 'N'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.widget',
                                                    'template' => 'products.2',
                                                    'properties' => [
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'MODE' => 'all',
                                                        'ACTION' => 'buy',
                                                        'PRICE_CODE' => [
                                                            'BASE'
                                                        ],
                                                        'DISCOUNT_SHOW' => 'Y',
                                                        'SLIDER_USE' => 'N',
                                                        'TITLE_SHOW' => 'N',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'BLOCKS_HEADER_SHOW' => 'Y',
                                                        'BLOCKS_HEADER_TEXT' => 'Товары',
                                                        'BLOCKS_HEADER_ALIGN' => 'left',
                                                        'BLOCKS_DESCRIPTION_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                                        'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'FORM_ID' => '#FORMS_PRODUCT_ID#',
                                                        'FORM_PROPERTY_PRODUCT' => '#FORMS_PRODUCT_FIELDS_PRODUCT_ID#',
                                                        'DELAY_USE' => 'Y',
                                                        'OFFERS_LIMIT' => '0',
                                                        'QUICK_VIEW_USE' => 'Y',
                                                        'QUICK_VIEW_DETAIL' => 'N',
                                                        'QUICK_VIEW_TEMPLATE' => 2,
                                                        'QUICK_VIEW_PROPERTY_CODE' => [
                                                            'PROPERTY_TYPE',
                                                            'PROPERTY_QUANTITY_OF_STRIPS',
                                                            'PROPERTY_POWER',
                                                            'PROPERTY_PROCREATOR',
                                                            'PROPERTY_SCOPE',
                                                            'PROPERTY_DISPLAY',
                                                            'PROPERTY_WEIGTH',
                                                            'PROPERTY_ENERGY_CONSUMPTION',
                                                            'PROPERTY_SETTINGS',
                                                            'PROPERTY_COMPOSITION',
                                                            'PROPERTY_LENGTH',
                                                            'PROPERTY_SEASON',
                                                            'PROPERTY_PATTERN'
                                                        ],
                                                        'QUICK_VIEW_PROPERTY_MARKS_HIT' => 'HIT',
                                                        'QUICK_VIEW_PROPERTY_MARKS_NEW' => 'NEW',
                                                        'QUICK_VIEW_PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'QUICK_VIEW_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_DELAY_USE' => 'Y',
                                                        'QUICK_VIEW_MARKS_SHOW' => 'Y',
                                                        'QUICK_VIEW_MARKS_ORIENTATION' => 'horizontal',
                                                        'QUICK_VIEW_GALLERY_PREVIEW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_SHOW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_MODE' => 'number',
                                                        'QUICK_VIEW_ACTION' => 'buy',
                                                        'QUICK_VIEW_COUNTER_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_MODE' => 'preview',
                                                        'QUICK_VIEW_PROPERTIES_SHOW' => 'Y',
                                                        'QUICK_VIEW_DETAIL_SHOW' => 'Y',
                                                        'PROPERTY_CATEGORY' => 'CATEGORY',
                                                        'PROPERTY_ORDER_USE' => 'ORDER_USE',
                                                        'PROPERTY_MARKS_HIT' => 'HIT',
                                                        'PROPERTY_MARKS_NEW' => 'NEW',
                                                        'PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'PROPERTY_PICTURES' => 'PICTURES',
                                                        'OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'COMPARE_PATH' => '#SITE_DIR#catalog/compare.php',
                                                        'COMPARE_NAME' => 'compare',
                                                        'SHOW_PRICE_COUNT' => '1',
                                                        'TABS_ALIGN' => 'left',
                                                        'BORDERS' => 'N',
                                                        'BORDERS_STYLE' => 'squared',
                                                        'MARKS_SHOW' => 'Y',
                                                        'NAME_POSITION' => 'middle',
                                                        'NAME_ALIGN' => 'left',
                                                        'PRICE_ALIGN' => 'start',
                                                        'IMAGE_SLIDER_SHOW' => 'Y',
                                                        'COUNTER_SHOW' => 'Y',
                                                        'OFFERS_USE' => 'Y',
                                                        'OFFERS_ALIGN' => 'left',
                                                        'OFFERS_VIEW' => 'default',
                                                        'VOTE_SHOW' => 'Y',
                                                        'VOTE_ALIGN' => 'left',
                                                        'VOTE_MODE' => 'rating',
                                                        'QUANTITY_SHOW' => 'Y',
                                                        'QUANTITY_MODE' => 'number',
                                                        'QUANTITY_ALIGN' => 'left',
                                                        'USE_COMPARE' => 'Y',
                                                        'CONVERT_CURRENCY' => 'N',
                                                        'USE_PRICE_COUNT' => 'N',
                                                        'PRICE_VAT_INCLUDE' => 'N'
                                                    ]
                                                ]
                                            ],
                                            'tiles.3' => [
                                                'name' => 'Плитки 3',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.widget',
                                                    'template' => 'products.4',
                                                    'properties' => [
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                                        'MODE' => 'all',
                                                        'ACTION' => 'buy',
                                                        'PRICE_CODE' => [
                                                            'BASE'
                                                        ],
                                                        'DISCOUNT_SHOW' => 'Y',
                                                        'SLIDER_USE' => 'N',
                                                        'TITLE_SHOW' => 'N',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'BLOCKS_HEADER_SHOW' => 'Y',
                                                        'BLOCKS_HEADER_TEXT' => 'Товары',
                                                        'BLOCKS_HEADER_ALIGN' => 'left',
                                                        'BLOCKS_DESCRIPTION_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                                        'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'FORM_ID' => '#FORMS_PRODUCT_ID#',
                                                        'FORM_PROPERTY_PRODUCT' => '#FORMS_PRODUCT_FIELDS_PRODUCT_ID#',
                                                        'ORDER_FAST_USE' => 'N',
                                                        'DELAY_USE' => 'Y',
                                                        'OFFERS_LIMIT' => '0',
                                                        'QUICK_VIEW_USE' => 'Y',
                                                        'QUICK_VIEW_DETAIL' => 'N',
                                                        'QUICK_VIEW_TEMPLATE' => 2,
                                                        'QUICK_VIEW_LAZYLOAD_USE' => 'N',
                                                        'QUICK_VIEW_PROPERTY_CODE' => [
                                                            'PROPERTY_TYPE',
                                                            'PROPERTY_QUANTITY_OF_STRIPS',
                                                            'PROPERTY_POWER',
                                                            'PROPERTY_PROCREATOR',
                                                            'PROPERTY_SCOPE',
                                                            'PROPERTY_DISPLAY',
                                                            'PROPERTY_WEIGTH',
                                                            'PROPERTY_ENERGY_CONSUMPTION',
                                                            'PROPERTY_SETTINGS',
                                                            'PROPERTY_COMPOSITION',
                                                            'PROPERTY_LENGTH',
                                                            'PROPERTY_SEASON',
                                                            'PROPERTY_PATTERN'
                                                        ],
                                                        'QUICK_VIEW_PROPERTY_MARKS_HIT' => 'HIT',
                                                        'QUICK_VIEW_PROPERTY_MARKS_NEW' => 'NEW',
                                                        'QUICK_VIEW_PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'QUICK_VIEW_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'QUICK_VIEW_DELAY_USE' => 'Y',
                                                        'QUICK_VIEW_MARKS_SHOW' => 'Y',
                                                        'QUICK_VIEW_MARKS_ORIENTATION' => 'horizontal',
                                                        'QUICK_VIEW_GALLERY_PREVIEW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_SHOW' => 'Y',
                                                        'QUICK_VIEW_QUANTITY_MODE' => 'number',
                                                        'QUICK_VIEW_ACTION' => 'buy',
                                                        'QUICK_VIEW_COUNTER_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_SHOW' => 'Y',
                                                        'QUICK_VIEW_DESCRIPTION_MODE' => 'preview',
                                                        'QUICK_VIEW_PROPERTIES_SHOW' => 'Y',
                                                        'QUICK_VIEW_DETAIL_SHOW' => 'Y',
                                                        'PROPERTY_CATEGORY' => 'CATEGORY',
                                                        'PROPERTY_ORDER_USE' => 'ORDER_USE',
                                                        'PROPERTY_MARKS_HIT' => 'HIT',
                                                        'PROPERTY_MARKS_NEW' => 'NEW',
                                                        'PROPERTY_MARKS_RECOMMEND' => 'RECOMMEND',
                                                        'PROPERTY_PICTURES' => 'PICTURES',
                                                        'PROPERTY_STORES_SHOW' => 'STORES_SHOW',
                                                        'PROPERTY_ARTICLE' => 'ARTICLE',
                                                        'OFFERS_PROPERTY_PICTURES' => 'PICTURES',
                                                        'OFFERS_PROPERTY_STORES_SHOW' => 'STORES_SHOW',
                                                        'OFFERS_PROPERTY_ARTICLE' => 'ARTICLE',
                                                        'COMPARE_PATH' => '#SITE_DIR#catalog/compare.php',
                                                        'COMPARE_NAME' => 'compare',
                                                        'SHOW_PRICE_COUNT' => '1',
                                                        'TABS_ALIGN' => 'left',
                                                        'BORDERS' => 'N',
                                                        'BORDERS_STYLE' => 'squared',
                                                        'ARTICLE_SHOW' => 'Y',
                                                        'MARKS_SHOW' => 'Y',
                                                        'NAME_POSITION' => 'middle',
                                                        'NAME_ALIGN' => 'left',
                                                        'PRICE_ALIGN' => 'start',
                                                        'IMAGE_SLIDER_SHOW' => 'Y',
                                                        'IMAGE_SLIDER_NAV_SHOW' => 'N',
                                                        'IMAGE_SLIDER_OVERLAY_USE' => 'Y',
                                                        'COUNTER_SHOW' => 'Y',
                                                        'OFFERS_USE' => 'Y',
                                                        'OFFERS_ALIGN' => 'left',
                                                        'OFFERS_VIEW' => 'default',
                                                        'VOTE_SHOW' => 'Y',
                                                        'VOTE_ALIGN' => 'left',
                                                        'VOTE_MODE' => 'rating',
                                                        'QUANTITY_SHOW' => 'Y',
                                                        'QUANTITY_MODE' => 'number',
                                                        'QUANTITY_ALIGN' => 'left',
                                                        'USE_COMPARE' => 'Y',
                                                        'CONVERT_CURRENCY' => 'N',
                                                        'USE_PRICE_COUNT' => 'N',
                                                        'PRICE_VAT_INCLUDE' => 'N'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'shares' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.shares',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_SHARES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_SHARES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'center',
                                                        'HEADER_BLOCK_TEXT' => 'Акции',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'LINK_USE' => 'Y',
                                                        'DATE_SHOW' => 'Y',
                                                        'DATE_FORMAT' => 'd.m.Y',
                                                        'SEE_ALL_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'blocks.1' => [
                                                'name' => 'Блоки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.shares',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_SHARES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_SHARES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'center',
                                                        'HEADER_BLOCK_TEXT' => 'Акции',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'LINE_COUNT' => 1,
                                                        'LINK_USE' => 'Y',
                                                        'DESCRIPTION_USE' => 'N',
                                                        'DATE_SHOW' => 'Y',
                                                        'DATE_FORMAT' => 'd.m.Y',
                                                        'SEE_ALL_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.shares',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_SHARES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_SHARES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'center',
                                                        'HEADER_BLOCK_TEXT' => 'Акции',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'LINE_COUNT' => 2,
                                                        'LINK_USE' => 'Y',
                                                        'DESCRIPTION_USE' => 'Y',
                                                        'SEE_ALL_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'services' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.9',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'LINK_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'blocks.1' => [
                                                'name' => 'Блоки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.8',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 1,
                                                        'LINK_USE' => 'Y',
                                                        'INDENT_IMAGE_USE' => 'N',
                                                        'DESCRIPTION_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.6',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'DESCRIPTION_USE' => 'Y',
                                                        'LINK_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.3' => [
                                                'name' => 'Плитки 3',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.7',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'LINK_USE' => 'Y',
                                                        'DESCRIPTION_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'blocks.2' => [
                                                'name' => 'Блоки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.8',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 1,
                                                        'LINK_USE' => 'Y',
                                                        'INDENT_IMAGE_USE' => 'Y',
                                                        'DESCRIPTION_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.4' => [
                                                'name' => 'Плитки 4',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'ALIGNMENT' => 'center',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DETAIL_SHOW' => 'Y',
                                                        'DETAIL_TEXT' => 'Подробнее',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.5' => [
                                                'name' => 'Плитки 5',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '5',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_PRICE' => 'PRICE',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'TEMPLATE_VIEW' => 'mosaic',
                                                        'PRICE_SHOW' => 'N',
                                                        'BUTTON_SHOW' => 'Y',
                                                        'BUTTON_TYPE' => 'order',
                                                        'BUTTON_FORM_ID' => '#FORMS_SERVICE_ID#',
                                                        'FORM_FIELD' => '#FORMS_SERVICE_FIELDS_SERVICE_ID#',
                                                        'FORM_TEMPLATE' => '.default',
                                                        'FORM_TITLE' => 'Заказать услугу',
                                                        'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                                        'BUTTON_TEXT' => 'ЗАКАЗАТЬ',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.6' => [
                                                'name' => 'Плитки 6',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'LINK_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'wide.1' => [
                                                'name' => 'Широкий 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.4',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'N',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'NUMBER_SHOW' => 'Y',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DETAIL_SHOW' => 'Y',
                                                        'DETAIL_TEXT' => 'Подробнее',
                                                        'PARALLAX_USE' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.7' => [
                                                'name' => 'Плитки 7',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.services',
                                                    'template' => 'template.5',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CATALOGS_SERVICES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CATALOGS_SERVICES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_BLOCK_SHOW' => 'Y',
                                                        'HEADER_BLOCK_POSITION' => 'left',
                                                        'HEADER_BLOCK_TEXT' => 'Услуги',
                                                        'DESCRIPTION_BLOCK_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'LINK_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'stages' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.stages',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Этапы работ',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COUNT_SHOW' => 'N',
                                                        'ELEMENT_DESCRIPTION_SHOW' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.stages',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Этапы работ',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 2,
                                                        'ELEMENT_SHOW_DESCRIPTION' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'advantages' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'icons.1' => [
                                                'name' => 'Иконки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.advantages',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'ELEMENTS_ROW_COUNT' => 3,
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Преимущества',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.advantages',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'LINE_COUNT' => 3,
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Преимущества',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'VIEW' => 'number',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'chess.1' => [
                                                'name' => 'Шахматка 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.advantages',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_2_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_2_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Преимущества',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'BACKGROUND_SIZE' => 'cover',
                                                        'ARROW_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.advantages',
                                                    'template' => 'template.11',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ADVANTAGES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ADVANTAGES_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER' => 'Преимущества',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'PREVIEW_SHOW' => 'Y',
                                                        'NUMBER_SHOW' => 'Y',
                                                        'COLUMNS' => 2,
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'video' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'wide.1' => [
                                                'name' => 'Широкий 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.video',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_VIDEO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_VIDEO_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_MODE' => 'code',
                                                        'ELEMENT' => 'video_1',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_LINK' => 'LINK',
                                                        'HEADER_SHOW' => 'N',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'WIDE' => 'Y',
                                                        'HEIGHT' => 400,
                                                        'FADE' => 'N',
                                                        'SHADOW_USE' => 'N',
                                                        'THEME' => 'light',
                                                        'PARALLAX_USE' => 'N',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'projects' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.projects',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_PROJECTS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_PROJECTS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'left',
                                                        'HEADER_TEXT' => 'Реализованные проекты',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'WIDE' => 'Y',
                                                        'COLUMNS' => 3,
                                                        'TABS_USE' => 'Y',
                                                        'TABS_POSITION' => 'left',
                                                        'LINK_USE' => 'Y',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => '3600000',
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'DESC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'rates' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.rates',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_RATES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_RATES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '6',
                                                        'PROPERTY_LIST' => [
                                                            'PROPERTY_PRODUCT_COUNT',
                                                            'PROPERTY_PHOTO_COUNT',
                                                            'PROPERTY_DOCUMENTS_COUNT',
                                                            'PROPERTY_DISK_SPACE'
                                                        ],
                                                        'PROPERTY_PRICE' => 'PRICE',
                                                        'PROPERTY_CURRENCY' => 'CURRENCY',
                                                        'PROPERTY_DISCOUNT' => 'DISCOUNT',
                                                        'PROPERTY_DISCOUNT_TYPE' => '',
                                                        'PROPERTY_DETAIL_URL' => '',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Тарифы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'VIEW' => 'tabs',
                                                        'TABS_POSITION' => 'center',
                                                        'SECTION_DESCRIPTION_SHOW' => 'Y',
                                                        'SECTION_DESCRIPTION_POSITION' => 'center',
                                                        'COUNTER_SHOW' => 'Y',
                                                        'COUNTER_TEXT' => 'ТАРИФ',
                                                        'PRICE_SHOW' => 'Y',
                                                        'DISCOUNT_SHOW' => 'Y',
                                                        'PREVIEW_SHOW' => 'Y',
                                                        'PROPERTIES_SHOW' => 'Y',
                                                        'BUTTON_SHOW' => 'N',
                                                        'SLIDER_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'staff' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.staff',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_STAFFS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_STAFFS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_POSITION' => 'POSITION',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Наша команда',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'POSITION_SHOW' => 'Y',
                                                        'SOCIALS_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.staff',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_STAFFS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_STAFFS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Наша команда',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'BUTTON_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'certificates' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.certificates',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_CERTIFICATES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_CERTIFICATES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Сертификаты',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 3,
                                                        'ALIGNMENT' => 'center',
                                                        'NAME_SHOW' => 'Y',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'faq' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'wide.1' => [
                                                'name' => 'Широкий 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.faq',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_FAQ_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_FAQ_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Вопрос - ответ',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'BY_SECTION' => 'N',
                                                        'ELEMENT_TEXT_ALIGN' => 'center',
                                                        'FOOTER_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'wide.2' => [
                                                'name' => 'Широкий 2 (Со вкладками)',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.faq',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_FAQ_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_FAQ_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Вопрос - ответ',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'BY_SECTION' => 'Y',
                                                        'TABS_POSITION' => 'center',
                                                        'ELEMENT_TEXT_ALIGN' => 'center',
                                                        'FOOTER_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'videos' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'slider.1' => [
                                                'name' => 'Слайдер 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.videos',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_VIDEO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_VIDEO_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '',
                                                        'PICTURE_SOURCES' => [
                                                            'service',
                                                            'preview',
                                                            'detail'
                                                        ],
                                                        'PICTURE_SERVICE_QUALITY' => 'sddefault',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_URL' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER' => 'Видеогалерея',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 3,
                                                        'FOOTER_SHOW' => 'N',
                                                        'SLIDER_USE' => 'Y',
                                                        'SLIDER_LOOP_USE' => 'N',
                                                        'SLIDER_AUTO_PLAY_USE' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'list.1' => [
                                                'name' => 'Список 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.videos',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_VIDEO_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_VIDEO_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '',
                                                        'PICTURE_SOURCES' => [
                                                            'service',
                                                            'preview',
                                                            'detail'
                                                        ],
                                                        'PICTURE_SERVICE_QUALITY' => 'sddefault',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'PROPERTY_URL' => 'LINK',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER' => 'Видеогалерея',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'reviews' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'list.1' => [
                                                'name' => 'Список 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.4',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'slider.1' => [
                                                'name' => 'Слайдер 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.7',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'SLIDER_NAV' => 'Y',
                                                        'SLIDER_DOTS' => 'Y',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'blocks.1' => [
                                                'name' => 'Блоки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.5',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'LINK_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'blocks.2' => [
                                                'name' => 'Блоки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.6',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 2,
                                                        'LINK_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'list.2' => [
                                                'name' => 'Список 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '2',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 1,
                                                        'LINK_USE' => 'N',
                                                        'SLIDER_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'slider.2' => [
                                                'name' => 'Слайдер 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'COUNTER_SHOW' => 'Y',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'slider.3' => [
                                                'name' => 'Слайдер 3',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.3',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'slider.4' => [
                                                'name' => 'Слайдер 4',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.reviews',
                                                    'template' => 'template.9',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_REVIEWS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_REVIEWS_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SECTIONS_MODE' => 'id',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Отзывы',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'Y',
                                                        'FOOTER_BUTTON_TEXT' => 'Показать все',
                                                        'LIST_PAGE_URL' => '#SITE_DIR_MACROS#company/reviews/',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'articles' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:widget',
                                                    'template' => 'articles',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ARTICLES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ARTICLES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_CENTER' => 'N',
                                                        'HEADER' => 'Статьи',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_CENTER' => 'N',
                                                        'DESCRIPTION' => 'В нашем каталоге представлены последние линейки спецтехники, систем Закажите консультацию по любому товару у наших специалистов или соберите свой заказ прямо на сайте. Мы подготовим для вас индивидуальное коммерческое предложение и вышлем персональный блок бонусов и скидок.',
                                                        'BIG_FIRST_BLOCK' => 'N',
                                                        'HEADER_ELEMENT_SHOW' => 'Y',
                                                        'DESCRIPTION_ELEMENT_SHOW' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:widget',
                                                    'template' => 'articles',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_ARTICLES_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_ARTICLES_IBLOCK_ID#',
                                                        'ELEMENTS_COUNT' => '3',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_CENTER' => 'N',
                                                        'HEADER' => 'Статьи',
                                                        'DESCRIPTION_SHOW' => 'Y',
                                                        'DESCRIPTION_CENTER' => 'N',
                                                        'DESCRIPTION' => 'В нашем каталоге представлены последние линейки спецтехники, систем Закажите консультацию по любому товару у наших специалистов или соберите свой заказ прямо на сайте. Мы подготовим для вас индивидуальное коммерческое предложение и вышлем персональный блок бонусов и скидок.',
                                                        'BIG_FIRST_BLOCK' => 'Y',
                                                        'HEADER_ELEMENT_SHOW' => 'Y',
                                                        'DESCRIPTION_ELEMENT_SHOW' => 'Y',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'about' => [
                                        'type' => 'simple',
                                        'properties' => [
                                            'margin' => [
                                                'top' => ['value' => 100, 'measure' => 'px'],
                                                'bottom' => ['value' => 100, 'measure' => 'px']
                                            ]
                                        ],
                                        'component' => [
                                            'code' => 'bitrix:main.include',
                                            'template' => '.default',
                                            'properties' => [
                                                'AREA_FILE_SHOW' => 'file',
                                                'PATH' => '#SITE_DIR_MACROS#include/company.php',
                                                'EDIT_TEMPLATE' => ''
                                            ]
                                        ]
                                    ],
                                    'brands' => [
                                        'type' => 'variable',
                                        'variants' => [
                                            'slider.1' => [
                                                'name' => 'Слайдер 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.brands',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BRANDS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BRANDS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Нам доверяют',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 4,
                                                        'EFFECT' => 'none',
                                                        'TRANSPARENCY' => '0',
                                                        'SLIDER_USE' => 'Y',
                                                        'SLIDER_NAVIGATION' => 'Y',
                                                        'SLIDER_DOTS' => 'Y',
                                                        'SLIDER_LOOP' => 'N',
                                                        'SLIDER_AUTO_USE' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.1' => [
                                                'name' => 'Плитки 1',
                                                'properties' => [
                                                    'margin' => [
                                                        'bottom' => ['value' => 50, 'measure' => 'px']
                                                    ]
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.brands',
                                                    'template' => 'template.1',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BRANDS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BRANDS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '8',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'LINK_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Нам доверяют',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'LINE_COUNT' => 4,
                                                        'ALIGNMENT' => 'center',
                                                        'EFFECT' => 'none',
                                                        'TRANSPARENCY' => '0',
                                                        'SLIDER_USE' => 'N',
                                                        'FOOTER_SHOW' => 'N',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ],
                                            'tiles.2' => [
                                                'name' => 'Плитки 2',
                                                'properties' => [
                                                    'bottom' => ['value' => 50, 'measure' => 'px']
                                                ],
                                                'component' => [
                                                    'code' => 'intec.universe:main.brands',
                                                    'template' => 'template.2',
                                                    'properties' => [
                                                        'IBLOCK_TYPE' => '#CONTENT_BRANDS_IBLOCK_TYPE#',
                                                        'IBLOCK_ID' => '#CONTENT_BRANDS_IBLOCK_ID#',
                                                        'SECTIONS_MODE' => 'id',
                                                        'ELEMENTS_COUNT' => '4',
                                                        'SETTINGS_USE' => 'Y',
                                                        'LAZYLOAD_USE' => 'N',
                                                        'HEADER_SHOW' => 'Y',
                                                        'HEADER_POSITION' => 'center',
                                                        'HEADER_TEXT' => 'Нам доверяют',
                                                        'DESCRIPTION_SHOW' => 'N',
                                                        'COLUMNS' => 4,
                                                        'LINK_USE' => 'Y',
                                                        'BACKGROUND_USE' => 'N',
                                                        'OPACITY' => '50',
                                                        'GRAYSCALE' => 'N',
                                                        'FOOTER_SHOW' => 'Y',
                                                        'FOOTER_POSITION' => 'center',
                                                        'FOOTER_BUTTON_SHOW' => 'N',
                                                        'LIST_PAGE_URL' => '',
                                                        'SECTION_URL' => '',
                                                        'DETAIL_URL' => '',
                                                        'CACHE_TYPE' => 'A',
                                                        'CACHE_TIME' => 3600000,
                                                        'SORT_BY' => 'SORT',
                                                        'ORDER_BY' => 'ASC'
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ])
                            ]]
                        ]]
                    ]]
                ]], $templateBlocksLoad('pages-main-blocks.', [
                    'contacts' => [
                        'order' => 2,
                        'type' => 'variable',
                        'variants' => [
                            'simple.1' => [
                                'name' => 'Обычный 1',
                                'component' => [
                                    'code' => 'bitrix:main.include',
                                    'template' => '.default',
                                    'properties' => [
                                        'AREA_FILE_SHOW' => 'file',
                                        'PATH' => '#SITE_DIR_MACROS#include/index/narrow.left/contacts/simple.1.php'
                                    ]
                                ]
                            ],
                            'list.1' => [
                                'name' => 'Список 1',
                                'component' => [
                                    'code' => 'intec.universe:main.widget',
                                    'template' => 'contacts.1',
                                    'properties' => [
                                        'IBLOCK_TYPE' => '#CONTENT_CONTACTS_IBLOCK_TYPE#',
                                        'IBLOCK_ID' => '#CONTENT_CONTACTS_IBLOCK_ID#',
                                        'SETTINGS_USE' => 'Y',
                                        'NEWS_COUNT' => '20',
                                        'MAIN' => '#CONTENT_CONTACTS_CONTACT_ID#',
                                        'PROPERTY_CODE' => [
                                            'ADDRESS',
                                            'CITY',
                                            'PHONE',
                                            'MAP'
                                        ],
                                        'PROPERTY_ADDRESS' => 'ADDRESS',
                                        'PROPERTY_CITY' => 'CITY',
                                        'PROPERTY_PHONE' => 'PHONE',
                                        'PROPERTY_MAP' => 'MAP',
                                        'MAP_VENDOR' => 'yandex',
                                        'WEB_FORM_TEMPLATE' => '.default',
                                        'WEB_FORM_ID' => '#FORMS_FEEDBACK_ID#',
                                        'WEB_FORM_NAME' => 'Задать вопрос',
                                        'WEB_FORM_CONSENT_URL' => '#SITE_DIR#company/consent/',
                                        'FEEDBACK_BUTTON_TEXT' => 'Написать',
                                        'FEEDBACK_TEXT' => 'Связаться с руководителем',
                                        'FEEDBACK_IMAGE' => '#TEMPLATE_PATH#/images/face.png',
                                        'ADDRESS_SHOW' => 'Y',
                                        'PHONE_SHOW' => 'Y',
                                        'SHOW_FORM' => 'Y',
                                        'FEEDBACK_TEXT_SHOW' => 'Y',
                                        'FEEDBACK_IMAGE_SHOW' => 'Y',
                                        'CACHE_TYPE' => 'A',
                                        'CACHE_TIME' => 3600000
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]))
            ]]
        ], [
            'code' => 'template-footer',
            'properties' => [
                'class' => 'intec-template-footer'
            ],
            'containers' => [[
                'area' => 'footer'
            ]]
        ]]
    ]
];