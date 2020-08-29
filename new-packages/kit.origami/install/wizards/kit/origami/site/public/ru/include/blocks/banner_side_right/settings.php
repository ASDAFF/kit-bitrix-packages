<?php
return [
    'block'  =>
        [
            'name'    => \Bitrix\Main\Localization\Loc::getMessage('BANNER_RIGHT_TITLE'),
            'section' => 'banners',
        ],
    'fields' =>
        [
        ],
    'groups' =>
        [
        ],
    'ext'    =>
        [
            'js'  =>
                [$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_banner_3/script.js'
                ],
            'css' =>
                [$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_banner_3/style.css'
                ],
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
