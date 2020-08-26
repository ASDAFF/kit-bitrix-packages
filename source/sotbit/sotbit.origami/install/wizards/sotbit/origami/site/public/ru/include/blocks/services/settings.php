<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_TITLE'),
			'section' => 'services',
		],
    'fields' =>
		[
			'title' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_TITLE'),
					'type' => 'input',
					'group' => 'titles',
					'value' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_TITLE_VALUE'),
                ],
            'sort_by1' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_IBORD1'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'ACTIVE_FROM',
                    'values' =>
                        [
                            "ID" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FID"),
                            "NAME" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FNAME"),
                            "ACTIVE_FROM" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FACT"),
                            "SORT" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FSORT"),
                            "TIMESTAMP_X" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FTSAMP")
                        ],
                ],
            'sort_order1' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_IBBY1'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'DESC',
                    'values' =>
                        [
                            "ASC" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_ASC"),
                            "DESC" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_DESC")
                        ],
                ],
            'sort_by2' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_IBORD2'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'SORT',
                    'values' =>
                        [
                            "ID" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FID"),
                            "NAME" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FNAME"),
                            "ACTIVE_FROM" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FACT"),
                            "SORT" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FSORT"),
                            "TIMESTAMP_X" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_FTSAMP")
                        ],
                ],
            'sort_order2' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_FIELD_IBBY2'),
					'type' => 'select',
                    'group' => 'settings',
                    'value' => 'ASC',
                    'values' =>
                        [
                            "ASC" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_ASC"),
                            "DESC" => \Bitrix\Main\Localization\Loc::getMessage("SERVICES_DESC_DESC")
                        ],
				],
		],
	'groups' =>
		[
			'titles' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_GROUP_TITLES'),
                ],
            'settings' =>
				[
					'name' => \Bitrix\Main\Localization\Loc::getMessage('SERVICES_GROUP_SETTINGS'),
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
	'style' =>
		[
		],
]
?>