<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_TITLE_STOCKS'),
			'section' => 'promotions',
		],
    'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'titles',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_TITLE_VALUE'),
                ],
            'sort_by1' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_IBORD1'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'ACTIVE_FROM',
                    'values' =>
                        [
                            "ID" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FID"),
                            "NAME" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FNAME"),
                            "ACTIVE_FROM" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FACT"),
                            "SORT" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FSORT"),
                            "TIMESTAMP_X" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FTSAMP")
                        ],
                ],
            'sort_order1' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_IBBY1'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'DESC',
                    'values' =>
                        [
                            "ASC" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_ASC"),
                            "DESC" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_DESC")
                        ],
                ],
            'sort_by2' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_IBORD2'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'SORT',
                    'values' =>
                        [
                            "ID" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FID"),
                            "NAME" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FNAME"),
                            "ACTIVE_FROM" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FACT"),
                            "SORT" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FSORT"),
                            "TIMESTAMP_X" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_FTSAMP")
                        ],
                ],
            'sort_order2' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_FIELD_IBBY2'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'ASC',
                    'values' =>
                        [
                            "ASC" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_ASC"),
                            "DESC" => \Bitrix\Main\Localization\Loc::getMessage("PROMOTIONS_DESC_DESC")
                        ],
				],
		],
	'groups' =>
		[
			'titles' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_GROUP_TITLES'),
                ],
            'settings' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('PROMOTIONS_GROUP_SETTINGS'),
				],
		],
	'ext' =>
		[
			'js' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_stocks/script.js'
				],
			'css' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/origami_stocks/style.css'
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
