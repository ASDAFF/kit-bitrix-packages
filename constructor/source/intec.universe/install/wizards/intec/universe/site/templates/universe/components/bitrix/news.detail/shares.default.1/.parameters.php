<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;

/**
 * @var string $siteTemplate
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([
        'SORT' => 'ASC'
    ], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]));

    $hProperties = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        return [
            'key' => $arProperty['CODE'],
            'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
        ];
    };

    $hPropertiesString = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['MULTIPLE'] !== 'Y')
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesElements = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['MULTIPLE'] === 'Y' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $hPropertiesSections = function ($iIndex, $arProperty) {
        if (empty($arProperty['CODE']))
            return ['skip' => true];

        if ($arProperty['PROPERTY_TYPE'] === 'G' && $arProperty['MULTIPLE'] === 'Y' && empty($arProperty['USER_TYPE']))
            return [
                'key' => $arProperty['CODE'],
                'value' => '[' . $arProperty['CODE'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    };
}
$arTemplateParameters = [];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** Banner */

$arTemplateParameters['BANNER_WIDE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_BANNER_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['BANNER_HEIGHT'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_BANNER_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        '350px' => '350px',
        '400px' => '400px',
        '450px' => '450px',
        '500px' => '500px'
    ],
    'DEFAULT' => '400px',
    'ADDITIONAL_VALUES' => 'Y'
];

/** Description */

$arTemplateParameters['DESCRIPTION_PROPERTY_DURATION'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_DESCRIPTION_PROPERTY_DURATION'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesString),
    'ADDITIONAL_VALUES' => 'Y'
];

/** Promo */

$arTemplateParameters['PROMO_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PROMO_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
    'intec.universe:main.advantages',
    'template.29',
    $siteTemplate,
    $arCurrentValues,
    'PROMO_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PROMO').'. '.$arParameter['NAME'];

        if (ArrayHelper::isIn($sKey, [
            'IBLOCK_TYPE',
            'IBLOCK_ID'
        ])) return true;

        return false;
    },
    Component::PARAMETERS_MODE_COMPONENT
));

/** Conditions */

$arTemplateParameters['CONDITIONS_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['CONDITIONS_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS_HEADER_DEFAULT')
];

$arTemplateParameters['CONDITIONS_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        'intec.universe:main.advantages',
        'template.7',
        $siteTemplate,
        $arCurrentValues,
        'CONDITIONS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_TYPE',
                'IBLOCK_ID'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'intec.universe:main.advantages',
        'template.7',
        $siteTemplate,
        $arCurrentValues,
        'CONDITIONS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_CONDITIONS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'COLUMNS'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);

/** Form */

$arTemplateParameters['FORM_SHOW'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_FORM_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FORM_SHOW'] === 'Y') {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
        Component::getParameters(
            'intec.universe:main.widget',
            'form.5',
            $siteTemplate,
            $arCurrentValues,
            'FORM_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_FORM') . '. ' . $arParameter['NAME'];

                if (ArrayHelper::isIn($sKey, [
                    'PROPERTY_HEADER',
                    'PROPERTY_DESCRIPTION'
                ])) return true;

                return false;
            },
            Component::PARAMETERS_MODE_COMPONENT
        ),
        Component::getParameters(
            'intec.universe:main.widget',
            'form.5',
            $siteTemplate,
            $arCurrentValues,
            'FORM_',
            function ($sKey, &$arParameter) {
                $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_FORM') . '. ' . $arParameter['NAME'];

                return true;
            },
            Component::PARAMETERS_MODE_TEMPLATE
        )
    );
}

/** Videos */

$arTemplateParameters['VIDEOS_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['VIDEOS_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS_HEADER_DEFAULT')
];

$arTemplateParameters['VIDEOS_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        "intec.universe:main.videos",
        "template.1",
        $siteTemplate,
        $arCurrentValues,
        'VIDEOS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS') . '. ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_TYPE',
                'IBLOCK_ID',
                'PROPERTY_URL'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'intec.universe:main.videos',
        'template.1',
        $siteTemplate,
        $arCurrentValues,
        'VIDEOS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_VIDEOS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'COLUMNS'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);

/** Gallery */

$arTemplateParameters['GALLERY_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['GALLERY_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY_HEADER_DEFAULT')
];

$arTemplateParameters['GALLERY_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        "intec.universe:main.gallery",
        "template.2",
        $siteTemplate,
        $arCurrentValues,
        'GALLERY_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY') . '. ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_TYPE',
                'IBLOCK_ID'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'intec.universe:main.gallery',
        'template.2',
        $siteTemplate,
        $arCurrentValues,
        'GALLERY_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_GALLERY').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'LINE_COUNT',
                'WIDE'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);

/** Sections */

$arTemplateParameters['SECTIONS_PROPERTY_SECTIONS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS_PROPERTY_SECTIONS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesSections),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['SECTIONS_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS_HEADER_DEFAULT')
];

$arTemplateParameters['SECTIONS_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        "intec.universe:main.sections",
        "template.1",
        $siteTemplate,
        $arCurrentValues,
        'SECTIONS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS') . '. ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_TYPE',
                'IBLOCK_ID'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'intec.universe:main.sections',
        'template.1',
        $siteTemplate,
        $arCurrentValues,
        'SECTIONS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SECTIONS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'LINE_COUNT'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);

/** Services */

$arTemplateParameters['SERVICES_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['SERVICES_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES_HEADER_DEFAULT')
];

$arTemplateParameters['SERVICES_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        "intec.universe:main.services",
        "template.8",
        $siteTemplate,
        $arCurrentValues,
        'SERVICES_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES') . '. ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'IBLOCK_TYPE',
                'IBLOCK_ID'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'intec.universe:main.services',
        'template.8',
        $siteTemplate,
        $arCurrentValues,
        'SERVICES_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_SERVICES').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'SETTINGS_USE',
                'LAZYLOAD_USE'
            ])) return false;

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    )
);

/** Products */

$arTemplateParameters['PRODUCTS_PROPERTY_ELEMENTS'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_PROPERTY_ELEMENTS'),
    'TYPE' => 'LIST',
    'VALUES' => $arProperties->asArray($hPropertiesElements),
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['PRODUCTS_HEADER'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_HEADER'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_HEADER_DEFAULT')
];

$arTemplateParameters['PRODUCTS_HEADER_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_HEADER_POSITION'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_LEFT'),
        'center' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_CENTER'),
        'right' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_HEADER_POSITION_RIGHT')
    ],
    'DEFAULT' => 'left'
];

$arUsedParameters = [
    'IBLOCK_TYPE',
    'IBLOCK_ID',
    'PRICE_CODE',
    'BASKET_URL',
    'HIDE_NOT_AVAILABLE_OFFERS',
    'OFFERS_CART_PROPERTIES',
    'OFFERS_PROPERTY_CODE',
    'OFFERS_SORT_FIELD',
    'OFFERS_SORT_ORDER',
    'OFFERS_LIMIT'
];

$arTemplateParameters = ArrayHelper::merge($arTemplateParameters,
    Component::getParameters(
        'bitrix:catalog.section',
        'catalog.tile.1',
        $siteTemplate,
        $arCurrentValues,
        'PRODUCTS_',
        function ($sKey, &$arParameter) use (&$arUsedParameters) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, $arUsedParameters)) return true;

            return false;
        },
        Component::PARAMETERS_MODE_COMPONENT
    ),
    Component::getParameters(
        'bitrix:catalog.section',
        'catalog.tile.1',
        $siteTemplate,
        $arCurrentValues,
        'PRODUCTS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS').'. '.$arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'LAZYLOAD_USE'
            ])) return false;

            return true;
        },
    Component::PARAMETERS_MODE_TEMPLATE
    )
);

unset ($arUsedParameters);

$arTemplateParameters['PRODUCTS_USE_COMPARE'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_USE_COMPARE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['PRODUCTS_COMPARE_NAME'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_PRODUCTS_COMPARE_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'compare'
];

/** Links */

$arTemplateParameters['LINKS_BUTTON'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS_BUTTON'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS_BUTTON_DEFAULT')
];

$arTemplateParameters['LINKS_SOCIAL_SHOW'] = [
    'PARENT' => 'DATA_SOURCE',
    'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS_SOCIAL_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LINKS_SOCIAL_SHOW'] === 'Y') {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        'bitrix:main.share',
        'flat',
        $siteTemplate,
        $arCurrentValues,
        'LINKS_',
        function ($sKey, &$arParameter) {
            $arParameter['NAME'] = Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS') . '. ' . $arParameter['NAME'];

            if (ArrayHelper::isIn($sKey, [
                'HANDLERS',
                'SHORTEN_URL_LOGIN',
                'SHORTEN_URL_KEY'
            ])) return true;

            return false;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));

    $arTemplateParameters['LINKS_TITLE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS_TITLE'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_NEWS_DETAIL_SHARES_DEFAULT_1_LINKS_TITLE_DEFAULT')
    ];
}