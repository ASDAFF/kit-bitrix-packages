<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [];

if (!empty($_REQUEST['site'])) {
    $sSite = $_REQUEST['site'];
} else if (!empty($_REQUEST['src_site'])) {
    $sSite = $_REQUEST['src_site'];
}

$arForms = [
    'TEMPLATE' => [],
    'ID' => [],
    'FIELDS' => []
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    if (Loader::includeModule('form'))
        include(__DIR__.'/parameters/base.php');
    elseif (Loader::includeModule('intec.startshop'))
        include(__DIR__.'/parameters/lite.php');
    else
        return;

    $arIBlockTypes = CIBlockParameters::GetIBlockTypes();
    $arIBlocksList = Arrays::fromDBResult(CIBlock::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'SITE_ID' => $sSite
    ]))->indexBy('ID');

    if (!empty($arCurrentValues['VIDEO_IBLOCK_TYPE']))
        $arIBlocksVideo = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['VIDEO_IBLOCK_TYPE'])
                return [
                    'key' => $arProperty['ID'],
                    'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arIBlocksVideo = $arIBlocksList->asArray(function ($sKey, $arProperty) {
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });

    if (!empty($arCurrentValues['BLOCKS_IBLOCK_TYPE']))
        $arIBlocksBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['BLOCKS_IBLOCK_TYPE'])
                return [
                    'key' => $arProperty['ID'],
                    'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arIBlocksBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) {
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });

    if (!empty($arCurrentValues['REVIEWS_IBLOCK_TYPE']))
        $arIBlocksReviews = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['REVIEWS_IBLOCK_TYPE'])
                return [
                    'key' => $arProperty['ID'],
                    'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arIBlocksReviews = $arIBlocksList->asArray(function ($sKey, $arProperty) {
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });

    if (!empty($arCurrentValues['LINKS_IBLOCK_TYPE']))
        $arIBlocksLinks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['LINKS_IBLOCK_TYPE'])
                return [
                    'key' => $arProperty['ID'],
                    'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
                ];

            return ['skip' => true];
        });
    else
        $arIBlocksLinks = $arIBlocksList->asArray(function ($sKey, $arProperty) {
            return [
                'key' => $arProperty['ID'],
                'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
            ];
        });

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyListMulti = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'F' && $arProperty['LIST_TYPE'] == 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyElement = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'E' && $arProperty['LIST_TYPE'] == 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyList = $arProperties->asArray($hPropertyList);
    $arPropertyListMulti = $arProperties->asArray($hPropertyListMulti);
    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyElement = $arProperties->asArray($hPropertyElement);
}

$bOrderUse = false;
$bBlocksUse = false;
$bVideoUse = false;
$bLinksUse = false;
$bReviewsUse = false;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    /** System settings */
    $arTemplateParameters['FORM_TEMPLATE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms['TEMPLATE'],
        'DEFAULT' => '.default'
    ];
    $arTemplateParameters['FORM_CONSENT'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_CONSENT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '#SITE_DIR#company/consent/'
    ];
    $arTemplateParameters['FORM_ORDER_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_ORDER_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms['ID'],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['FORM_ORDER_ID'])) {
        $bOrderUse = true;

        $arTemplateParameters['FORM_ORDER_FIELD'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_ORDER_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => ArrayHelper::getValue($arForms, ['FIELDS', 'ORDER']),
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['FORM_ORDER_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_ORDER_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_ORDER_TITLE_DEFAULT')
        ];
    }

    $arTemplateParameters['FORM_FEEDBACK_1_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms['ID'],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['FORM_FEEDBACK_1_ID'])) {
        $arTemplateParameters['FORM_FEEDBACK_1_FORM_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_TITLE_DEFAULT')
        ];
        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_DESCRIPTION_SHOW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_DESCRIPTION_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_1_BLOCK_DESCRIPTION_SHOW'] === 'Y') {
            $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_DESCRIPTION_TEXT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_DESCRIPTION_TEXT'),
                'TYPE' => 'STRING'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_VIEW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_VIEW_LEFT'),
                'right' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_VIEW_RIGHT'),
                'vertical' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_VIEW_VERTICAL')
            ],
            'DEFAULT' => 'left',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_1_BLOCK_VIEW'] === 'vertical') {
            $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_ALIGN_HORIZONTAL'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_1_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'left' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_1_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_LEFT'),
                    'center' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_1_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_CENTER'),
                    'right' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_1_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_RIGHT')
                ],
                'DEFAULT' => 'center'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_THEME'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_THEME'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'light' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_THEME_LIGHT'),
                'dark' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_THEME_DARK'),
            ],
            'DEFAULT' => 'dark'
        ];
        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_BG_COLOR'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BG_COLOR'),
            'TYPE' => 'STRING'
        ];
        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_BG_IMAGE_SHOW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BG_IMAGE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_1_BLOCK_BG_IMAGE_SHOW'] === 'Y') {
            $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_BG_IMAGE_PATH'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BG_IMAGE_PATH'),
                'TYPE' => 'STRING'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT_DEFAULT')
        ];
    }

    $arTemplateParameters['FORM_FEEDBACK_2_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms['ID'],
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['FORM_FEEDBACK_2_ID'])) {
        $arTemplateParameters['FORM_FEEDBACK_2_FORM_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_TITLE'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_TITLE_DEFAULT')
        ];
        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_DESCRIPTION_SHOW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_DESCRIPTION_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_2_BLOCK_DESCRIPTION_SHOW'] === 'Y') {
            $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_DESCRIPTION_TEXT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_DESCRIPTION_TEXT'),
                'TYPE' => 'STRING'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_VIEW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'left' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_VIEW_LEFT'),
                'right' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_VIEW_RIGHT'),
                'vertical' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_VIEW_VERTICAL')
            ],
            'DEFAULT' => 'left',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_2_BLOCK_VIEW'] === 'vertical') {
            $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    'left' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_LEFT'),
                    'center' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_CENTER'),
                    'right' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL_RIGHT')
                ],
                'DEFAULT' => 'center'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_THEME'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_THEME'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'light' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_THEME_LIGHT'),
                'dark' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_THEME_DARK'),
            ],
            'DEFAULT' => 'dark'
        ];
        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_BG_COLOR'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BG_COLOR'),
            'TYPE' => 'STRING'
        ];
        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_BG_IMAGE_SHOW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BG_IMAGE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['FORM_FEEDBACK_2_BLOCK_BG_IMAGE_SHOW'] === 'Y') {
            $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_BG_IMAGE_PATH'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BG_IMAGE_PATH'),
                'TYPE' => 'STRING'
            ];
        }

        $arTemplateParameters['FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT_DEFAULT')
        ];
    }

    $arTemplateParameters['BLOCKS_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['BLOCKS_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksBlocks,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['BLOCKS_IBLOCK_ID'])) {
        $bBlocksUse = true;

        $arDescriptionBlocksProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['BLOCKS_IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]))->indexBy('ID');

        $arTemplateParameters['BLOCKS_PROPERTY_NAME'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_NAME'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_PROPERTY_IMAGES'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_IMAGES'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyElement),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_PROPERTY_TEXT_ADDITIONAL'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_TEXT_ADDITIONAL'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_PROPERTY_THEME'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_THEME'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyList),
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_PROPERTY_VIEW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyList),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['BLOCKS_PROPERTY_DETAIL_NARROW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_DETAIL_NARROW'),
            'TYPE' => 'LIST',
            'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyCheckbox),
            'ADDITIONAL_VALUES' => 'Y'
        ];

        if (!empty($arCurrentValues['BLOCKS_PROPERTY_VIEW'])) {
            $arTemplateParameters['BLOCKS_PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW'),
                'TYPE' => 'LIST',
                'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyCheckbox),
                'ADDITIONAL_VALUES' => 'Y'
            ];
            $arTemplateParameters['BLOCKS_PROPERTY_COMPACT_POSITION'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_PROPERTY_COMPACT_POSITION'),
                'TYPE' => 'LIST',
                'VALUES' => $arDescriptionBlocksProperties->asArray($hPropertyList),
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }

        if (!empty($arCurrentValues['BLOCKS_PROPERTY_IMAGES'])) {
            $arTemplateParameters['BLOCKS_IMAGES_SHOW'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_IMAGES_SHOW'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ];
        }

        $arTemplateParameters['BLOCKS_BUTTON_TEXT'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BLOCKS_BUTTON_TEXT_DEFAULT')
        ];
    }

    $arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksVideo,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['VIDEO_IBLOCK_ID'])) {
        $arVideoProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['VIDEO_IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]))->indexBy('ID');

        $arTemplateParameters['VIDEO_PROPERTY_URL'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PROPERTY_URL'),
            'TYPE' => 'LIST',
            'VALUES' => $arVideoProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['VIDEO_PROPERTY_URL'])) {
            $bVideoUse = true;

            $arTemplateParameters['VIDEO_PICTURE_SOURCES'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SOURCES'),
                'TYPE' => 'LIST',
                'MULTIPLE' => 'Y',
                'VALUES' => [
                    'service' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SOURCES_SERIVCE'),
                    'preview' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SOURCES_PREVIEW'),
                    'detail' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SOURCES_DETAIL')
                ],
                'REFRESH' => 'Y'
            ];

            $arPictureSources = ArrayHelper::getValue($arCurrentValues, 'VIDEO_PICTURE_SOURCES');

            if (Type::isArray($arPictureSources)) {
                if (ArrayHelper::isIn('service', $arPictureSources)) {
                    $arTemplateParameters['VIDEO_PICTURE_SERVICE_QUALITY'] = [
                        'PARENT' => 'BASE',
                        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SERVICE_QUALITY'),
                        'TYPE' => 'LIST',
                        'VALUES' => [
                            'mqdefault' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SERVICE_QUALITY_MQ'),
                            'hqdefault' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SERVICE_QUALITY_HQ'),
                            'sddefault' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SERVICE_QUALITY_SD'),
                            'maxresdefault' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_PICTURE_SERVICE_QUALITY_MAX')
                        ],
                        'DEFAULT' => 'sddefault'
                    ];
                }
            }
        }
    }

    $arTemplateParameters['LINKS_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_LINKS_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['LINKS_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_LINKS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksLinks,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['LINKS_IBLOCK_ID'])) {
        $bLinksUse = true;

        $arLinksProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['LINKS_IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]))->indexBy('ID');

        $arTemplateParameters['LINKS_PROPERTY_LINK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_LINKS_PROPERTY_LINK'),
            'TYPE' => 'LIST',
            'VALUES' => $arLinksProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['LINKS_PROPERTY_NAME'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_LINKS_PROPERTY_NAME'),
            'TYPE' => 'LIST',
            'VALUES' => $arLinksProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['REVIEWS_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['REVIEWS_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocksReviews,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['REVIEWS_IBLOCK_ID'])) {
        $bReviewsUse = true;

        $arReviewsProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $arCurrentValues['REVIEWS_IBLOCK_ID'],
            'ACTIVE' => 'Y'
        ]))->indexBy('ID');

        $arTemplateParameters['REVIEWS_PROPERTY_POSITION'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_PROPERTY_POSITION'),
            'TYPE' => 'LIST',
            'VALUES' => $arReviewsProperties->asArray($hPropertyText),
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['REVIEWS_PROPERTY_POSITION'])) {
            $arTemplateParameters['REVIEWS_POSITION_SHOW'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_POSITION_SHOW'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ];
        }

        $arTemplateParameters['REVIEWS_FOOTER_BUTTON_SHOW'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_FOOTER_BUTTON_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['REVIEWS_FOOTER_BUTTON_SHOW'] === 'Y') {
            $arTemplateParameters['REVIEW_FOOTER_BUTTON_TEXT'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEW_FOOTER_BUTTON_TEXT'),
                'TYPE' => 'STRING',
                'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEW_FOOTER_BUTTON_TEXT_DEFAULT')
            ];
            $arTemplateParameters['REVIEW_LIST_PAGE_URL'] = [
                'PARENT' => 'BASE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEW_LIST_PAGE_URL'),
                'TYPE' => 'STRING'
            ];
        }
    }

    /** Properties */
    $arTemplateParameters['PROPERTY_MARK_HIT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_MARK_HIT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARK_NEW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_MARK_NEW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_MARK_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_MARK_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PRICE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_PRICE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PROPERTY_PRICE']) {
        $arTemplateParameters['PROPERTY_PRICE_OLD'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_PRICE_OLD'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyText,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_BUTTON_SHOW'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_BUTTON_SHOW'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
        $arTemplateParameters['PROPERTY_BUTTON_URL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_BUTTON_URL'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyText,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['PROPERTY_BUTTON_URL'])) {
            $arTemplateParameters['PROPERTY_BUTTON_TEXT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_BUTTON_TEXT'),
                'TYPE' => 'LIST',
                'VALUES' => $arPropertyText,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_PICTURE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_PICTURE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_CHARACTERISTICS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_CHARACTERISTICS'),
        'TYPE' => 'LIST',
        'VALUES' => ArrayHelper::merge(
            ArrayHelper::merge($arPropertyText, $arPropertyCheckbox),
            ArrayHelper::merge($arPropertyList, $arPropertyListMulti)
        ),
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y',
        'SIZE' => 9
    ];
    $arTemplateParameters['PROPERTY_ADDITIONAL_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_ADDITIONAL_TEXT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_ADDITIONAL_TEXT'])) {
        $arTemplateParameters['PROPERTY_ADDITIONAL_TEXT_PICTURE'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_ADDITIONAL_TEXT_PICTURE'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyFile,
            'ADDITIONAL_VALUES' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_PROJECTS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_PROJECTS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bReviewsUse) {
        $arTemplateParameters['PROPERTY_REVIEWS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_REVIEWS'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyElement,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_ADVANTAGES'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_ADVANTAGES'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_FAQ'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_FAQ'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_RECOMMEND'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_RECOMMEND'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bVideoUse) {
        $arTemplateParameters['PROPERTY_VIDEO'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_VIDEO'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyElement,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_NEWS_1'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_NEWS_1'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bBlocksUse) {
        $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_BLOCKS_1'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_BLOCKS_1'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyElement,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_2'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_ADVANTAGES_2'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if ($bBlocksUse) {
        $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_BLOCKS_2'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_BLOCKS_2'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyElement,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    if ($bLinksUse) {
        $arTemplateParameters['PROPERTY_TAB_DESCRIPTION_LINKS_1'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_DESCRIPTION_LINKS_1'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyElement,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_TAB_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyElement,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y',
        'SIZE' => 9,
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TAB_INFO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROPERTY_TAB_INFO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'MULTIPLE' => 'Y',
        'SIZE' => 9,
        'REFRESH' => 'Y'
    ];
}

/** Visual */
$arTemplateParameters['BANNER_BUTTONS_BACK_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_BUTTONS_BACK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

$arTemplateParameters['BANNER_WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_TYPE'])) {
    $arTemplateParameters['BANNER_TYPE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_TYPE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['BANNER_HEADER_H1'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_HEADER_H1'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['PROPERTY_PRICE'])) {
    $arTemplateParameters['BANNER_PRICE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_PRICE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if ($bOrderUse) {
    $arTemplateParameters['BANNER_ORDER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ORDER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['BANNER_ORDER_SHOW'] === 'Y') {
        $arTemplateParameters['BANNER_ORDER_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ORDER_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ORDER_TEXT_DEFAULT')
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_PICTURE'])) {
    $arTemplateParameters['BANNER_PICTURE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_PICTURE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_CHARACTERISTICS'])) {
    $arTemplateParameters['BANNER_CHARACTERISTICS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_CHARACTERISTICS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_ADDITIONAL_TEXT'])) {
    $arTemplateParameters['ADDITIONAL_TEXT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ADDITIONAL_TEXT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['ADDITIONAL_TEXT_SHOW'] === 'Y') {
        $arTemplateParameters['ADDITIONAL_TEXT_DARK'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ADDITIONAL_TEXT_DARK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['ADDITIONAL_TEXT_WIDE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_BANNER_ADDITIONAL_TEXT_WIDE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1']) ||
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_NEWS_1']) ||
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_BLOCKS_1']) ||
    !empty($arCurrentValues['FORM_FEEDBACK_1_ID']) ||
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_2']) ||
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_BLOCKS_2'] ||
    !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_LINKS_1']))
) {
    $arTemplateParameters['TAB_DESCRIPTION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAB_DESCRIPTION_SHOW'] === 'Y') {
        $arTemplateParameters['TAB_DESCRIPTION_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NAME_DEFAULT')
        ];

        if (!empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1'])) {
            $arTemplateParameters['TAB_DESCRIPTION_ADVANTAGES_1_COLUMNS'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_ADVANTAGES_1_COLUMNS'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    4 => '4',
                    5 => '5',
                    6 => '6',
                    7 => '7'
                ],
                'DEFAULT' => 5
            ];
        }

        if (!empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_NEWS_1'])) {
            $arTemplateParameters['TAB_DESCRIPTION_NEWS_1_COLUMNS'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NEWS_1_COLUMNS'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5'
                ],
                'DEFAULT' => 4
            ];
            $arTemplateParameters['TAB_DESCRIPTION_NEWS_1_DATE_SHOW'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NEWS_1_DATE_SHOW'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y'
            ];

            if ($arCurrentValues['TAB_DESCRIPTION_NEWS_1_DATE_SHOW'] === 'Y') {
                $arTemplateParameters['TAB_DESCRIPTION_NEWS_1_DATE_FORMAT'] = CIBlockParameters::GetDateFormat(
                    Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NEWS_1_DATE_FORMAT'),
                    'VISUAL'
                );
            }

            $arTemplateParameters['TAB_DESCRIPTION_NEWS_1_LINK_USE'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NEWS_1_LINK_USE'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y'
            ];

            if ($arCurrentValues['TAB_DESCRIPTION_NEWS_1_LINK_USE'] === 'Y') {
                $arTemplateParameters['TAB_DESCRIPTION_NEWS_1_LINK_BLANK'] = [
                    'PARENT' => 'VISUAL',
                    'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_NEWS_1_LINK_BLANK'),
                    'TYPE' => 'CHECKBOX',
                    'DEFAULT' => 'N'
                ];
            }
        }

        if ($bLinksUse && !empty($arCurrentValues['PROPERTY_TAB_DESCRIPTION_LINKS_1'])) {
            $arTemplateParameters['TAB_DESCRIPTION_LINKS_1_COLUMNS'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_LINKS_1_COLUMNS'),
                'TYPE' => 'LIST',
                'VALUES' => [
                    3 => '3',
                    4 => '4'
                ],
                'DEFAULT' => 3
            ];
            $arTemplateParameters['TAB_DESCRIPTION_LINKS_1_LINK_BLANK'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_DESCRIPTION_LINKS_1_LINK_BLANK'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N'
            ];
        }
    }
}

if (!empty($arCurrentValues['PROPERTY_TAB_VIDEO'])) {
    $arTemplateParameters['TAB_VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAB_VIDEO_SHOW'] === 'Y') {
        $arTemplateParameters['TAB_VIDEO_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_VIDEO_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_VIDEO_NAME_DEFAULT')
        ];
        $arTemplateParameters['TAB_VIDEO_COLUMNS'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_VIDEO_COLUMNS'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => '1',
                2 => '2',
                3 => '3',
                4 => '4',
                5 => '5'
            ],
            'DEFAULT' => 3
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_TAB_INFO'])) {
    $arTemplateParameters['TAB_INFO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_INFO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TAB_INFO_SHOW'] === 'Y') {
        $arTemplateParameters['TAB_INFO_NAME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_INFO_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_TAB_INFO_NAME_DEFAULT')
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_PROJECTS'])) {
    $arTemplateParameters['PROJECTS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROJECTS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['PROJECTS_SHOW'] === 'Y') {
        $arTemplateParameters['PROJECTS_WIDE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROJECTS_WIDE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];
        $arTemplateParameters['PROJECTS_COLUMNS'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_PROJECTS_COLUMNS'),
            'TYPE' => 'LIST',
            'VALUES' => ArrayHelper::merge([
                3 => '3',
                4 => '4'
            ], $arCurrentValues['PROJECTS_WIDE'] === 'Y' ? [5 => '5'] : []),
            'DEFAULT' => 4
        ];
    }
}

if ($bReviewsUse && !empty($arCurrentValues['PROPERTY_REVIEWS'])) {
    $arTemplateParameters['REVIEWS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_REVIEWS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
}

if (!empty($arCurrentValues['PROPERTY_ADVANTAGES'])) {
    $arTemplateParameters['ADVANTAGES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_ADVANTAGES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['ADVANTAGES_SHOW'] === 'Y') {
        $arTemplateParameters['ADVANTAGES_COLUMNS'] = [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_ADVANTAGES_COLUMNS'),
            'TYPE' => 'LIST',
            'VALUES' => [
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5
            ],
            'DEFAULT' => 4
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_FAQ'])) {
    $arTemplateParameters['FAQ_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_FAQ_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_RECOMMEND'])) {
    $arTemplateParameters['RECOMMEND_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_RECOMMEND_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['RECOMMEND_SHOW'] === 'Y') {
        $arTemplateParameters['RECOMMEND_DESCRIPTION'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_RECOMMEND_DESCRIPTION'),
            'TYPE' => 'STRING'
        ];
    }
}

if ($bVideoUse && !empty($arCurrentValues['PROPERTY_VIDEO'])) {
    $arTemplateParameters['VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_SERVICES_DEFAULT_2_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
}