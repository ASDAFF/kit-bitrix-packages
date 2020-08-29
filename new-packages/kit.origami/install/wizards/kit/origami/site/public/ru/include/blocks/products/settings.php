<?
$sectionTemplates = CComponentUtil::GetTemplatesList("bitrix:catalog.section", $_GET['template_id']);
$sectionTemplateArray = array();
foreach ($sectionTemplates as $sectionTemplate) {
    $sectionTemplateArray[$sectionTemplate['NAME']] = $sectionTemplate['NAME'];
}
return [
    'block' =>
        [
            'name' => \Bitrix\Main\Localization\Loc::getMessage('PRODUCTS_TITLE'),
            'section' => 'products',
        ],
    'fields' =>
        [
            'ajax' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PRODUCTS_AJAX_MODE'),
                    'type' => 'select',
                    'group' => 'settings',
                    'value' => 'Y',
                    'values' =>
                        [
                            "Y" => \Bitrix\Main\Localization\Loc::getMessage("PRODUCTS_AJAX_Y"),
                            "N" => \Bitrix\Main\Localization\Loc::getMessage("PRODUCTS_AJAX_N"),
                        ],
                ],
            'products_number' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PRODUCTS_PRODUCTS_NUMBER'),
                    'type' => 'input',
                    'group' => 'settings',
                    'value' => '5',
                ],
            'template' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PRODUCTS_TEMPLATE'),
                    'type' => 'select',
                    'group' => 'settings',
                    'value' => 'origami_section',
                    'values' => $sectionTemplateArray,
                ],

        ],
    'groups' =>
        [
            'settings' =>
                [
                    'name' => \Bitrix\Main\Localization\Loc::getMessage('PRODUCTS_GROUP_SETTINGS'),
                ],
        ],
    'ext' =>
        [
            'js'  =>
                [ $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/catalog.item/origami_item/script.js',
                ],
            'css' =>
                [ $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/catalog.item/origami_item/style.css',
                    $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/catalog.section/origami_section/style.css',
					$_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/sotbit/crosssell.collection/origami_default/style.css'
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