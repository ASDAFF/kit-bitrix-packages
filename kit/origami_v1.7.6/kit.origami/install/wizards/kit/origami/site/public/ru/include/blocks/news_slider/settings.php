<?
return [
	'block' =>
		[
			'name' => \Bitrix\Main\Localization\Loc::getMessage('NEWS_TITLE_SLIDER'),
			'section' => 'news',
		],
	'fields' =>
		[

		],
	'groups' =>
		[

		],
	'ext' =>
		[
			'js' =>
				[
				],
			'css' =>
				[$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/news.list/news_slider/style.css',
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
