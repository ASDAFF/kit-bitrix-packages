<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('BRANDS_TITLE_SQUARES'),
			'section' => 'brands',
		],
	'fields' =>
		[
            'count' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('BRANDS_FIELD_COUNT'),
					'type' => 'input',
					'group' => 'settings',
					'value' => "12",
                ],
		],
	'groups' =>
		[
            'settings' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('BRANDS_GROUP_SETTINGS'),
				],
		],
	'ext' =>
		[
			'js' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_brands_squares/script.js',
				],
			'css' =>
				[ $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_brands_squares/style.css',
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
