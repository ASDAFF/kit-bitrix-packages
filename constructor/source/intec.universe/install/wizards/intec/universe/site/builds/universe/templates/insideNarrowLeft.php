<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

return [
    'code' => 'insideNarrowLeft',
    'name' => 'Внутрянняя (С левой колонкой)',
    'sort' => 100,
    'condition' => [
        'type' => 'group',
        'operator' => 'and',
        'result' => 1,
        'conditions' => [[
            'type' => 'path',
            'value' => '/',
            'result' => 0
        ], [
            'type' => 'path',
            'value' => '/index.php',
            'result' => 0
        ], [
            'type' => 'parameter.page',
            'key' => 'template-menu-show',
            'logic' => '=',
            'value' => 1,
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
                'containers' => [[
                    'code' => 'template-header',
                    'order' => 0,
                    'properties' => [
                        'class' => 'intec-template-header'
                    ],
                    'area' => 'header'
                ], [
                    'code' => 'template-breadcrumb',
                    'order' => 1,
                    'properties' => [
                        'class' => 'intec-template-breadcrumb'
                    ],
                    'script' => '$GLOBALS[\'BreadCrumbIBlockType\'] = \'#CATALOGS_PRODUCTS_IBLOCK_TYPE#\';'."\n".'$GLOBALS[\'BreadCrumbIBlockId\'] = \'#CATALOGS_PRODUCTS_IBLOCK_ID#\';',
                    'component' => [
                        'code' => 'bitrix:breadcrumb',
                        'template' => '.default',
                        'properties' => [
                            'template' => '.default',
                            'START_FROM' => '',
                            'PATH' => '',
                            'SITE_ID' => '-'
                        ]
                    ]
                ], [
                    'code' => 'template-title',
                    'order' => 2,
                    'properties' => [
                        'class' => 'intec-template-title'
                    ],
                    'widget' => [
                        'code' => 'intec.constructor:title',
                        'template' => '.default',
                        'properties' => []
                    ]
                ], [
                    'code' => 'template-page',
                    'order' => 3,
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
                                        'ROOT_MENU_TYPE' => 'left',
                                        'IBLOCK_TYPE' => '',
                                        'IBLOCK_ID' => '',
                                        'MENU_CACHE_TYPE' => 'A',
                                        'MENU_CACHE_TIME' => 3600,
                                        'MENU_CACHE_USE_GROUPS' => 'N',
                                        'MENU_CACHE_GET_VARS' => '',
                                        'MAX_LEVEL' => 2,
                                        'CHILD_MENU_TYPE' => 'left',
                                        'USE_EXT' => 'N',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'N'
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
                                'containers' => [[
                                    'widget' => [
                                        'code' => 'intec.constructor:content',
                                        'template' => '.default',
                                        'properties' => []
                                    ]
                                ]]
                            ]]
                        ]]
                    ]]
                ]]
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