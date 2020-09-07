<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_TITLE'),
			'section' => 'popular_categories',
		],
	'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'titles',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('PC_FIELD_TITLE_VALUE'),
				],
            'link_catalog' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_LINK_CATALOG'),
                    'type' => 'input',
                    'group' => 'settings',
                    'value' => "/catalog/",
                ],
            'top_depth' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_TOP_DEPTH'),
                    'type' => 'input',
                    'group' => 'settings',
                    'value' => "1",
                ],
            'count_sections' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_COUNT_SECTIONS'),
                    'type' => 'input',
                    'group' => 'settings',
                    'value' => '6',
                ],
            'show_subsections' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_SHOW_INCLUDE_SECTIONS_NAME'),
                    'type' => 'select',
                    'group' => 'settings',
                    'value' => 'Y',
                    'values' =>
                        [
                            "Y" => \Bitrix\Main\Localization\Loc::getMessage("PC_SHOW_INCLUDE_SECTIONS_YES"),
                            "N" => \Bitrix\Main\Localization\Loc::getMessage("PC_SHOW_INCLUDE_SECTIONS_NO")
                        ],
                ],
            'show_description' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_SHOW_DESCRIPTION'),
                    'type' => 'select',
                    'group' => 'settings',
                    'value' => 'Y',
                    'values' =>
                        [
                            "Y" => \Bitrix\Main\Localization\Loc::getMessage("PC_SHOW_INCLUDE_SECTIONS_YES"),
                            "N" => \Bitrix\Main\Localization\Loc::getMessage("PC_SHOW_INCLUDE_SECTIONS_NO")
                        ],
                ],
            'image_from' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_IMAGE_FROM'),
                    'type' => 'select',
                    'group' => 'settings',
                    'value' => 'UF_PHOTO_DETAIL',
                    'values' =>
                        [
                            "PICTURE" => \Bitrix\Main\Localization\Loc::getMessage("PC_IMAGE_FROM_PICTURE"),
                            "UF_PHOTO_DETAIL" => \Bitrix\Main\Localization\Loc::getMessage("PC_IMAGE_FROM_UF_PHOTO_DETAIL")
                        ],
                ],
		],
	'groups' =>
		[
			'titles' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_GROUP_TITLES'),

				],
            'settings' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_GROUP_SETTINGS'),
                ],
		],
	'ext' =>
		[
			'js' =>
				[
				],
			'css' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/catalog.section.list/origami_popular_categories_advanced/style.css'
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