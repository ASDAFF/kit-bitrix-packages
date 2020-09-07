<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_TITLE'),
			'section' => 'soc',
		],
	'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'text',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_FIELD_TITLE_VALUE'),
				],
			'chanelId' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_FIELD_CHANEL_ID'),
					'type' => 'input',
					'group' => 'config',
					'value' => 'UCljk41PuLLNRkcrxPOkj4Vg',
				],
			'apiKey' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_FIELD_API_KEY'),
					'type' => 'input',
					'group' => 'config',
					'value' => 'AIzaSyBXqtYHYvze0TNb8XUgykEqiB4F7YPcfoY',
                ],
            'count' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_FIELD_COUNT'),
					'type' => 'input',
					'group' => 'config',
					'value' => '4',
                ],
		],
	'groups' =>
		[
			'text' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_GROUP_TITLES'),
				],
			'config' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('YOUTUBE_GROUP_CONFIG'),
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