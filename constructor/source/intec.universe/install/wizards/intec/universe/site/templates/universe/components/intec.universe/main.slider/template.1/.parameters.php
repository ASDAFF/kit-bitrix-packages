<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyTextMultiple = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'Y')
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
    $hPropertyList = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyTextMultiple = $arProperties->asArray($hPropertyTextMultiple);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arPropertyList = $arProperties->asArray($hPropertyList);
}

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['PROPERTY_HEADER_OVER'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_HEADER_OVER'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_LINK'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_LINK'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_LINK'])) {
        $arTemplateParameters['PROPERTY_LINK_BLANK'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_LINK_BLANK'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['PROPERTY_BUTTON_SHOW'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_BUTTON_SHOW'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyCheckbox,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
            $arTemplateParameters['PROPERTY_BUTTON_TEXT'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_BUTTON_TEXT'),
                'TYPE' => 'LIST',
                'VALUES' => $arPropertyText,
                'ADDITIONAL_VALUES' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_TEXT_POSITION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_TEXT_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TEXT_ALIGN'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_TEXT_ALIGN'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyList,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TEXT_HALF'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_TEXT_HALF'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_PICTURE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_PICTURE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['PROPERTY_PICTURE'])) {
        $arTemplateParameters['PROPERTY_PICTURE_ALIGN_VERTICAL'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_PICTURE_ALIGN_VERTICAL'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyList,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    $arTemplateParameters['PROPERTY_ADDITIONAL'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_ADDITIONAL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyTextMultiple,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_SCHEME'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_SCHEME'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_FADE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_FADE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyCheckbox,
        'ADDITIONAL_VALUES' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_VIDEO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_MP4'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_VIDEO_FILE_MP4'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_WEBM'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_VIDEO_FILE_WEBM'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_VIDEO_FILE_OGV'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PROPERTY_VIDEO_FILE_OGV'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        500 => '500px',
        550 => '550px',
        600 => '600px',
        650 => '650px',
        700 => '700px',
        750 => '750px',
        800 => '800px'
    ],
    'ADDITIONAL_VALUES' => 'Y',
    'DEFAULT' => 600
];

if (!empty($arCurrentValues['PROPERTY_HEADER'])) {
    $arTemplateParameters['HEADER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEADER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['HEADER_SHOW'] === 'Y') {
        $arTemplateParameters['HEADER_H1'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEADER_H1'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['HEADER_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEADER_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1'),
                2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_2'),
                3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_3'),
                4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_4'),
                5 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_5')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_DESCRIPTION'])) {
    $arTemplateParameters['DESCRIPTION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_DESCRIPTION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['DESCRIPTION_SHOW'] === 'Y') {
        $arTemplateParameters['DESCRIPTION_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_DESCRIPTION_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1'),
                2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_2'),
                3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_3'),
                4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_4'),
                5 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_5')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_HEADER_OVER'])) {
    $arTemplateParameters['HEADER_OVER_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEADER_OVER_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['HEADER_OVER_SHOW'] === 'Y') {
        $arTemplateParameters['HEADER_OVER_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_HEADER_OVER_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1')
            ],
            'DEFAULT' => 1
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_BUTTON_SHOW'])) {
    $arTemplateParameters['BUTTON_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_BUTTON_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1'),
            2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_2'),
            3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_3'),
            4 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_4'),
        ],
        'DEFAULT' => 1
    ];
}

$arTemplateParameters['ORDER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ORDER_SHOW'] === 'Y') {
    /**
     * @var array $arForms - список форм
     * @var array $arFormFields список полей выбранной формы
     */

    if (Loader::includeModule('form'))
        include(__DIR__.'/parameters/base.php');
    elseif (Loader::includeModule('intec.startshop'))
        include(__DIR__.'/parameters/lite.php');
    else
        return;

    $arTemplates = [];

    foreach ($rsTemplates as $arTemplate)
        $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);

    $arTemplateParameters['ORDER_FORM_ID'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arForms,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['ORDER_FORM_ID'])) {
        $arTemplateParameters['ORDER_FORM_TEMPLATE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'ADDITIONAL_VALUES' => 'Y',
            'DEFAULT' => '.default'
        ];
        $arTemplateParameters['ORDER_FORM_FIELD'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_FIELD'),
            'TYPE' => 'LIST',
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        ];
        $arTemplateParameters['ORDER_FORM_TITLE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_TITLE'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_TITLE_DEFAULT')
        ];
        $arTemplateParameters['ORDER_FORM_CONSENT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_FORM_CONSENT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '#SITE_DIR#company/consent/'
        ];
        $arTemplateParameters['ORDER_BUTTON'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_BUTTON'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ORDER_BUTTON_DEFAULT')
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_PICTURE'])) {
    $arTemplateParameters['PICTURE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_PICTURE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];
}

if (
    !empty($arCurrentValues['PROPERTY_VIDEO']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_MP4']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_WEBM']) ||
    !empty($arCurrentValues['PROPERTY_VIDEO_FILE_OGV'])
) {
    $arTemplateParameters['VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_ADDITIONAL'])) {
    $arTemplateParameters['ADDITIONAL_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ADDITIONAL_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['ADDITIONAL_SHOW'] === 'Y') {
        $arTemplateParameters['ADDITIONAL_VIEW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_ADDITIONAL_VIEW'),
            'TYPE' => 'LIST',
            'VALUES' => [
                1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1'),
                2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_2'),
                3 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_3')
            ],
            'DEFAULT' => 1
        ];
    }
}

$arTemplateParameters['BUTTONS_BACK_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_BUTTONS_BACK_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BUTTONS_BACK_SHOW'] === 'Y') {
    $arTemplateParameters['BUTTONS_BACK_LINK'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_BUTTONS_BACK_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['SLIDER_NAV_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_NAV_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_NAV_SHOW'] === 'Y') {
    $arTemplateParameters['SLIDER_NAV_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_NAV_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1')
        ]
    ];
}

$arTemplateParameters['SLIDER_DOTS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_DOTS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_DOTS_SHOW'] === 'Y') {
    $arTemplateParameters['SLIDER_DOTS_VIEW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_DOTS_VIEW'),
        'TYPE' => 'LIST',
        'VALUES' => [
            1 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_1'),
            2 => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_VIEW_2')
        ]
    ];
}

$arTemplateParameters['SLIDER_LOOP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_LOOP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['SLIDER_AUTO_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_AUTO_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_AUTO_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_AUTO_TIME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_AUTO_TIME'),
        'TYPE' => 'STRING',
        'DEFAULT' => '10000'
    ];
    $arTemplateParameters['SLIDER_AUTO_HOVER'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SLIDER_TEMPLATE_1_SLIDER_AUTO_HOVER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}