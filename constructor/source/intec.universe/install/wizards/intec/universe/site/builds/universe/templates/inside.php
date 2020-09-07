<?php return [
    'code' => 'inside',
    'name' => 'Внутрянняя',
    'sort' => 200,
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
                        'class' => 'intec-template-page'
                    ],
                    'containers' => [[
                        'code' => 'template-page-wrapper',
                        'properties' => [
                            'class' => 'intec-template-page-wrapper'
                        ],
                        'containers' => [[
                            'code' => 'template-page-content',
                            'properties' => [
                                'class' => 'intec-template-page-content'
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