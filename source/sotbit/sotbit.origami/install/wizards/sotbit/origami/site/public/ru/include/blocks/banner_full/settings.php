<?php
return [
    'block'  =>
        [
            'name'    => \Bitrix\Main\Localization\Loc::getMessage('BANNER_TITLE_FULL'),
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
                [
                ],
            'css' =>
                [$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_banner_2/style.css'
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
