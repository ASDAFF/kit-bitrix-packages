<?php
return [
    'block' =>
        [
            'name' => \Bitrix\Main\Localization\Loc::getMessage('FORM_TITLE'),
            'section' => 'other',
        ],
    'fields' =>
        [
            'title' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('FORM_FIELD_TITLE'),
                    'type' => 'input',
                    'group' => 'titles',
                    'value' => \Bitrix\Main\Localization\Loc::getMessage('FORM_FIELD_TITLE_VALUE'),
                ],
        ],
    'groups' =>
        [
            'titles' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('FORM_GROUP_TITLES'),
                ],
        ],
    'ext' =>
        [
            'js' =>
                [
                ],
            'css' =>
                [$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/form.result.new/sotbit_webform_1/style.css',
                ],
        ],
    'style'  =>
        [
            'padding-top' => [
                'value' => '0'
            ],
            'padding-bottom' => [
                'value' => '0'
            ],
        ],
]
?>