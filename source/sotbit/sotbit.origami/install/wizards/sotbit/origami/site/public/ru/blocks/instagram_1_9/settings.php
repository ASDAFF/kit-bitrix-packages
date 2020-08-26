<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_TITLE'),
			'section' => 'soc',
		],
	'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'titles',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TITLE_VALUE'),
				],
            'title_text' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TITLE_TEXT'),
                    'type' => 'input',
                    'group' => 'titles',
                    'value' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TITLE_TEXT_VALUE'),
                ],
            'text' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TEXT'),
                    'type' => 'input',
                    'group' => 'titles',
                    'value' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_TEXT_VALUE'),
                ],
			'login' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_LOGIN'),
					'type' => 'input',
					'group' => 'config',
					'value' => 'sotbit_ru',
                ],
            'count' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_FIELD_COUNT'),
					'type' => 'input',
					'group' => 'config',
					'value' => '11',
                ],
		],
	'groups' =>
		[
			'titles' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_GROUP_TITLES'),

				],
			'config' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_1_GROUP_CONFIG'),

				],
		],
    'ext'    =>
        [
            'css' =>
                [ $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/sotbit/instagram/origami_insta_1/style.css',
                ],
            'include_head' => true
        ],
    'style'  =>
        [
            'padding-top' => [
                'value' => '30'
            ],
            'padding-bottom' => [
                'value' => '20'
            ],
        ],
]
?>
