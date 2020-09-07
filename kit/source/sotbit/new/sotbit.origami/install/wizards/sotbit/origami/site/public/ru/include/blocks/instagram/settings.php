<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_TITLE'),
			'section' => 'soc',
		],
	'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'titles',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_FIELD_TITLE_VALUE'),
				],
			'login' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_FIELD_LOGIN'),
					'type' => 'input',
					'group' => 'config',
					'value' => 'sotbit_ru',
                ],
            'count' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_FIELD_COUNT'),
					'type' => 'input',
					'group' => 'config',
					'value' => '4',
                ],
		],
	'groups' =>
		[
			'titles' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_GROUP_TITLES'),

				],
			'config' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_GROUP_CONFIG'),

				],
		],
	'ext' =>
		[
			'js' =>
				[
				],
			'css' =>
				[
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