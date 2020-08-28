<?php
return [
    'block'  =>
        [
            'name'    => \Bitrix\Main\Localization\Loc::getMessage('ABOUT_TITLE'),
            'section' => 'about',
        ],
    'fields' =>
        [
            'title' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('ABOUT_FIELD_TITLE'),
                    'type' => 'input',
                    'group' => 'titles',
                    'value' => \Bitrix\Main\Localization\Loc::getMessage('ABOUT_FIELD_TITLE_VALUE'),
                ],
        ],
    'groups' =>
        [
            'titles' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('ABOUT_GROUP_TITLES'),
                ],
        ],
    'ext'    =>
        [
            'js'  =>
                [ $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/blocks/about/script.js',
                ],
            'css' =>
                [ $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/blocks/about/style.css',
                ],
            'include_head' => true
        ],
    'style'  =>
        [
            'padding-top' => [
                'value' => '30'
            ],
            'padding-bottom' => [
                'value' => '40'
            ],
            'background-image' => [
                'value' => '#ORIGAMI_BACKGROUND_ABOUT#'
            ],
            'background-repeat' => [
                'value' => 'no-repeat'
            ],
            'background-attachment' => [
                'value' => 'fixed'
            ]
        ],
]
?>
