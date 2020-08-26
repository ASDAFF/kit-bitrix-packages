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
                'value' => '15'
            ],
            'padding-bottom' => [
                'value' => '15'
            ],
        ],
]
?>