<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_TITLE_SIMPLE'),
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
                    'value' => "2",
                ],
            'count_sections' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PC_COUNT_SECTIONS'),
                    'type' => 'input',
                    'group' => 'settings',
                    'value' => '8',
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
				[],
			'css' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/catalog.section.list/origami_popular_categories_simple/style.css'
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