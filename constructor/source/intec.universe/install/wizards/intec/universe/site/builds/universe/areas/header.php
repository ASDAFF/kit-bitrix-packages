<?php return [
    'code' => 'header',
    'name' => 'Шапка',
    'sort' => 100,
    'container' => [
        'containers' => [[
            'code' => 'panel',
            'order' => 0,
            'widget' => [
                'code' => 'intec.constructor:panel',
                'template' => '.default',
                'properties' => []
            ]
        ], [
            'code' => 'button-scroll',
            'order' => 1,
            'component' => [
                'code' => 'intec.universe:widget',
                'template' => 'buttontop',
                'properties' => [
                    'RADIUS' => 10,
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => 3600
                ]
            ]
        ], [
            'code' => 'basket-fixed',
            'order' => 2,
            'variator' => [
                'variants' => [[
                    'code' => 'template.1',
                    'name' => 'Шаблон 1',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:sale.basket.small',
                                'template' => 'template.1',
                                'properties' => [
                                    'SETTINGS_USE' => 'Y',
                                    'PANEL_SHOW' => 'Y',
                                    'COMPARE_SHOW' => 'Y',
                                    'COMPARE_CODE' => 'compare',
                                    'COMPARE_IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                    'COMPARE_IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                    'AUTO' => 'Y',
                                    'FORM_ID' => '#FORMS_CALL_ID#',
                                    'FORM_TITLE' => 'Заказать звонок',
                                    'BASKET_SHOW' => 'Y',
                                    'FORM_SHOW' => 'Y',
                                    'PERSONAL_SHOW' => 'Y',
                                    'DELAYED_SHOW' => 'Y',
                                    'CATALOG_URL' => '#SITE_DIR#catalog/',
                                    'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                    'ORDER_URL' => '#ORDER_PAGE_URL#',
                                    'COMPARE_URL' => '#SITE_DIR#catalog/compare.php',
                                    'PERSONAL_URL' => '#SITE_DIR#personal/profile/',
                                    'CONSENT_URL' => '#SITE_DIR#company/consent/'
                                ]
                            ]
                        ]]
                    ]
                ], [
                    'code' => 'template.2',
                    'name' => 'Шаблон 2',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:sale.basket.small',
                                'template' => 'template.2',
                                'properties' => [
                                    'SETTINGS_USE' => 'Y',
                                    'PANEL_SHOW' => 'Y',
                                    'COMPARE_SHOW' => 'Y',
                                    'COMPARE_CODE' => 'compare',
                                    'COMPARE_IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                    'COMPARE_IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                    'AUTO' => 'Y',
                                    'FORM_ID' => '#FORMS_CALL_ID#',
                                    'FORM_TITLE' => 'Заказать звонок',
                                    'BASKET_SHOW' => 'Y',
                                    'FORM_SHOW' => 'Y',
                                    'PERSONAL_SHOW' => 'Y',
                                    'SBERBANK_ICON_SHOW' => 'Y',
                                    'QIWI_ICON_SHOW' => 'Y',
                                    'YANDEX_MONEY_ICON_SHOW' => 'Y',
                                    'VISA_ICON_SHOW' => 'Y',
                                    'MASTERCARD_ICON_SHOW' => 'Y',
                                    'DELAYED_SHOW' => 'Y',
                                    'CATALOG_URL' => '#SITE_DIR#catalog/',
                                    'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                    'ORDER_URL' => '#ORDER_PAGE_URL#',
                                    'COMPARE_URL' => '#SITE_DIR#catalog/compare.php',
                                    'PERSONAL_URL' => '#SITE_DIR#personal/profile/',
                                    'CONSENT_URL' => '#SITE_DIR#company/consent/'
                                ]
                            ]
                        ]]
                    ]
                ]]
            ]
        ], [
            'code' => 'basket-notifications',
            'order' => 3,
            'variator' => [
                'variants' => [[
                    'code' => 'template.1',
                    'name' => 'Шаблон 1',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:sale.basket.small',
                                'template' => 'notifications.1',
                                'properties' => [
                                    'BASKET_URL' => '#SITE_DIR#personal/basket/'
                                ]
                            ]
                        ]]
                    ]
                ]]
            ]
        ], [
            'code' => 'mobile-blocks.panel',
            'order' => 4,
            'variator' => [
                'variants' => [[
                    'code' => 'template.1',
                    'name' => 'Шаблон 1',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:sale.basket.small',
                                'template' => 'panel.1',
                                'properties' => [
                                    'COMPARE_SHOW' => 'Y',
                                    'COMPARE_CODE' => 'compare',
                                    'COMPARE_IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                                    'COMPARE_IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                                    'SETTINGS_USE' => 'Y',
                                    'FORM_ID' => '#FORMS_CALL_ID#',
                                    'BASKET_SHOW' => 'Y',
                                    'FORM_SHOW' => 'Y',
                                    'PERSONAL_SHOW' => 'Y',
                                    'FORM_TITLE' => 'Заказать звонок',
                                    'DELAYED_SHOW' => 'Y',
                                    'CATALOG_URL' => '#SITE_DIR#catalog/',
                                    'BASKET_URL' => '#SITE_DIR#personal/basket/',
                                    'ORDER_URL' => '#SITE_DIR#personal/basket/order.php',
                                    'COMPARE_URL' => '#SITE_DIR#catalog/compare.php',
                                    'PERSONAL_URL' => '#SITE_DIR#personal/profile/',
                                    'CONSENT_URL' => '#SITE_DIR#company/consent/',
                                    'REGISTER_URL' => '#SITE_DIR#personal/profile/',
                                    'FORGOT_PASSWORD_URL' => '#SITE_DIR#personal/profile/?forgot_password=yes',
                                    'PROFILE_URL' => '#SITE_DIR#personal/profile/'
                                ]
                            ]
                        ]]
                    ]
                ]]
            ]
        ], [
            'code' => 'component',
            'order' => 5,
            'component' => [
                'code' => 'intec.universe:main.header',
                'template' => 'template.1',
                'properties' => [
                    'SETTINGS_USE' => 'Y',
                    'REGIONALITY_USE' => 'N',
                    'CONTACTS_REGIONALITY_USE' => 'Y',
                    'CONTACTS_REGIONALITY_STRICT' => 'N',
                    'CONTACTS_IBLOCK_TYPE' => '#CONTENT_CONTACTS_IBLOCK_TYPE#',
                    'CONTACTS_IBLOCK_ID' => '#CONTENT_CONTACTS_IBLOCK_ID#',
                    'CONTACTS_PROPERTY_PHONE' => 'PHONE',
                    'CONTACTS_PROPERTY_CITY' => 'CITY',
                    'CONTACTS_PROPERTY_ADDRESS' => 'ADDRESS',
                    'CONTACTS_PROPERTY_SCHEDULE' => 'WORK_TIME',
                    'CONTACTS_PROPERTY_EMAIL' => 'EMAIL',
                    'CONTACTS_PROPERTY_REGION' => 'REGIONS',
                    'SEARCH_NUM_CATEGORIES' => 1,
                    'SEARCH_TOP_COUNT' => 5,
                    'SEARCH_ORDER' => 'date',
                    'SEARCH_USE_LANGUAGE_GUESS' => 'Y',
                    'SEARCH_CHECK_DATES' => 'N',
                    'SEARCH_SHOW_OTHERS' => 'N',
                    'SEARCH_TIPS_USE' => 'Y',
                    'SEARCH_MODE' => 'site',
                    'COMPARE_IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                    'COMPARE_IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                    'COMPARE_CODE' => 'compare',
                    'MENU_MAIN_ROOT' => 'top',
                    'MENU_MAIN_CHILD' => 'left',
                    'MENU_MAIN_LEVEL' => 4,
                    'MENU_MAIN_IBLOCK_TYPE' => '#CATALOGS_PRODUCTS_IBLOCK_TYPE#',
                    'MENU_MAIN_IBLOCK_ID' => '#CATALOGS_PRODUCTS_IBLOCK_ID#',
                    'MENU_INFO_ROOT' => 'info',
                    'MENU_INFO_CHILD' => 'left',
                    'MENU_INFO_LEVEL' => 1,
                    'LOGOTYPE' => '#SITE_DIR#include/logotype.php',
                    'PHONES' => [
                        '#SITE_PHONE#'
                    ],
                    'ADDRESS' => '#SITE_ADDRESS#',
                    'EMAIL' => '#SITE_MAIL#',
                    'TAGLINE' => 'Слоган',
                    'MENU_MAIN_PROPERTY_IMAGE' => 'UF_IMAGE_MENU',
                    'FORMS_CALL_ID' => '#FORMS_CALL_ID#',
                    'FORMS_CALL_TEMPLATE' => '.default',
                    'MENU_POPUP_FORMS_FEEDBACK_ID' => '#FORMS_FEEDBACK_ID#',
                    'MENU_POPUP_FORMS_FEEDBACK_TEMPLATE' => '.default',
                    'SOCIAL_VK' => 'https://vk.com',
                    'SOCIAL_INSTAGRAM' => 'https://instagram.com',
                    'SOCIAL_FACEBOOK' => 'https://facebook.com',
                    'SOCIAL_TWITTER' => 'https://twitter.com',
                    'BANNER' => 'template.1',
                    'BANNER_DISPLAY' => 'main',
                    'BANNER_IBLOCK_TYPE' => '#CONTENT_BANNERS_IBLOCK_TYPE#',
                    'BANNER_IBLOCK_ID' => '#CONTENT_BANNERS_IBLOCK_ID#',
                    'BANNER_ELEMENTS_COUNT' => '',
                    'BANNER_SECTIONS_MODE' => 'code',
                    'BANNER_LAZYLOAD_USE' => 'N',
                    'BANNER_BLOCKS_USE' => 'Y',
                    'BANNER_BLOCKS_IBLOCK_TYPE' => '#CONTENT_BANNERS_SMALL_IBLOCK_TYPE#',
                    'BANNER_BLOCKS_IBLOCK_ID' => '#CONTENT_BANNERS_SMALL_IBLOCK_ID#',
                    'BANNER_BLOCKS_MODE' => 'N',
                    'BANNER_BLOCKS_ELEMENTS_COUNT' => 4,
                    'BANNER_BLOCKS_POSITION' => 'right',
                    'BANNER_BLOCKS_EFFECT_FADE' => 'Y',
                    'BANNER_BLOCKS_EFFECT_SCALE' => 'Y',
                    'BANNER_BLOCKS_INDENT' => 'N',
                    'BANNER_HEIGHT' => 500,
                    'BANNER_WIDE' => 'N',
                    'BANNER_ROUNDED' => 'N',
                    'BANNER_HEADER_SHOW' => 'Y',
                    'BANNER_HEADER_VIEW' => 4,
                    'BANNER_DESCRIPTION_SHOW' => 'Y',
                    'BANNER_DESCRIPTION_VIEW' => 1,
                    'BANNER_HEADER_OVER_SHOW' => 'Y',
                    'BANNER_HEADER_OVER_VIEW' => 1,
                    'BANNER_BUTTON_VIEW' => 1,
                    'BANNER_ORDER_SHOW' => 'N',
                    'BANNER_ORDER_FORM_ID' => "#FORMS_QUESTION_ID#",
                    'BANNER_ORDER_FORM_TEMPLATE' => '.default',
                    'BANNER_ORDER_FORM_TITLE' => 'Узнать стоимость',
                    'BANNER_ORDER_FORM_CONSENT' => '#SITE_DIR#company/consent/',
                    'BANNER_ORDER_BUTTON' => 'Узнать стоимость',
                    'BANNER_PICTURE_SHOW' => 'Y',
                    'BANNER_VIDEO_SHOW' => 'Y',
                    'BANNER_ADDITIONAL_SHOW' => 'Y',
                    'BANNER_ADDITIONAL_VIEW' => 3,
                    'BANNER_SLIDER_NAV_SHOW' => 'Y',
                    'BANNER_SLIDER_NAV_VIEW' => 1,
                    'BANNER_SLIDER_DOTS_SHOW' => 'Y',
                    'BANNER_SLIDER_DOTS_VIEW' => 1,
                    'BANNER_SLIDER_DOTS' => 'Y',
                    'BANNER_SLIDER_LOOP' => 'N',
                    'BANNER_SLIDER_SPEED' => 500,
                    'BANNER_SLIDER_AUTO_USE' => 'N',
                    'BANNER_SORT_BY' => 'SORT',
                    'BANNER_ORDER_BY' => 'ASC',
                    'BANNER_PROPERTY_HEADER' => 'TITLE',
                    'BANNER_PROPERTY_DESCRIPTION' => 'DESCRIPTION',
                    'BANNER_PROPERTY_HEADER_OVER' => 'HEADER_OVER',
                    'BANNER_PROPERTY_LINK' => 'LINK',
                    'BANNER_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                    'BANNER_PROPERTY_BUTTON_SHOW' => 'BUTTON_SHOW',
                    'BANNER_PROPERTY_BUTTON_TEXT' => 'BUTTON_TEXT',
                    'BANNER_PROPERTY_TEXT_POSITION' => 'TEXT_POSITION',
                    'BANNER_PROPERTY_TEXT_ALIGN' => 'TEXT_ALIGN',
                    'BANNER_PROPERTY_TEXT_HALF' => 'TEXT_HALF',
                    'BANNER_PROPERTY_PICTURE' => 'PICTURE',
                    'BANNER_PROPERTY_PICTURE_ALIGN_VERTICAL' => 'PICTURE_ALIGN_VERTICAL',
                    'BANNER_PROPERTY_ADDITIONAL' => 'ADDITIONAL',
                    'BANNER_PROPERTY_SCHEME' => 'TEXT_DARK',
                    'BANNER_PROPERTY_FADE' => 'BACKGROUND_FADE',
                    'BANNER_PROPERTY_VIDEO' => 'BACKGROUND_VIDEO',
                    'BANNER_PROPERTY_VIDEO_FILE_MP4' => 'BACKGROUND_VIDEO_FILE_MP4',
                    'BANNER_PROPERTY_VIDEO_FILE_WEBM' => 'BACKGROUND_VIDEO_FILE_WEBM',
                    'BANNER_PROPERTY_VIDEO_FILE_OGV' => 'BACKGROUND_VIDEO_FILE_OGV',
                    'BANNER_BLOCKS_PROPERTY_LINK' => 'LINK',
			        'BANNER_BLOCKS_PROPERTY_LINK_BLANK' => 'LINK_BLANK',
                    'LOGOTYPE_SHOW' => 'Y',
                    'PHONES_SHOW' => 'Y',
                    'PHONES_SHOW_MOBILE' => 'Y',
                    'PHONES_ADVANCED_MODE' => 'Y',
                    'CONTACTS_ADDRESS_SHOW' => 'Y',
                    'CONTACTS_SCHEDULE_SHOW' => 'Y',
                    'CONTACTS_EMAIL_SHOW' => 'Y',
                    'TRANSPARENCY' => 'Y',
                    'LOGOTYPE_SHOW_FIXED' => 'Y',
                    'LOGOTYPE_SHOW_MOBILE' => 'Y',
                    'ADDRESS_SHOW' => 'Y',
                    'ADDRESS_SHOW_MOBILE' => 'Y',
                    'EMAIL_SHOW' => 'Y',
                    'EMAIL_SHOW_MOBILE' => 'Y',
                    'AUTHORIZATION_SHOW' => 'Y',
                    'AUTHORIZATION_SHOW_FIXED' => 'Y',
                    'AUTHORIZATION_SHOW_MOBILE' => 'Y',
                    'TAGLINE_SHOW' => 'Y',
                    'SEARCH_SHOW' => 'Y',
                    'SEARCH_SHOW_FIXED' => 'Y',
                    'SEARCH_SHOW_MOBILE' => 'Y',
                    'BASKET_SHOW' => 'Y',
                    'BASKET_SHOW_FIXED' => 'Y',
                    'BASKET_SHOW_MOBILE' => 'Y',
                    'BASKET_POPUP' => 'Y',
                    'DELAY_SHOW' => 'Y',
                    'DELAY_SHOW_FIXED' => 'Y',
                    'DELAY_SHOW_MOBILE' => 'Y',
                    'COMPARE_SHOW' => 'Y',
                    'COMPARE_SHOW_FIXED' => 'Y',
                    'COMPARE_SHOW_MOBILE' => 'Y',
                    'MENU_MAIN_SHOW' => 'Y',
                    'MENU_MAIN_SHOW_FIXED' => 'Y',
                    'MENU_MAIN_SHOW_MOBILE' => 'Y',
                    'MENU_MAIN_DELIMITERS' => 'Y',
                    'MENU_MAIN_SECTION_VIEW' => 'images',
                    'MENU_MAIN_SUBMENU_VIEW' => 'simple.1',
                    'MENU_MAIN_SECTION_COLUMNS_COUNT' => 3,
                    'MENU_MAIN_SECTION_ITEMS_COUNT' => 3,
                    'MENU_MAIN_CATALOG_LINKS' => [
                        '#SITE_DIR#catalog/'
                    ],
                    'FORMS_CALL_SHOW' => 'Y',
                    'FORMS_CALL_TITLE' => 'Заказать звонок',
                    'MENU_POPUP_TEMPLATE' => 1,
                    'MENU_POPUP_MODE' => 'simple',
                    'MENU_POPUP_THEME' => 'light',
                    'MENU_POPUP_BACKGROUND' => 'none',
                    'MENU_POPUP_CONTACTS_SHOW' => 'Y',
                    'MENU_POPUP_FORMS_FEEDBACK_SHOW' => 'Y',
                    'MENU_POPUP_FORMS_FEEDBACK_TITLE' => 'Задать вопрос',
                    'MENU_POPUP_SOCIAL_SHOW' => 'Y',
                    'MENU_POPUP_BASKET_SHOW' => 'Y',
                    'MENU_POPUP_DELAY_SHOW' => 'Y',
                    'MENU_POPUP_COMPARE_SHOW' => 'Y',
                    'MENU_POPUP_AUTHORIZATION_SHOW' => 'Y',
                    'DESKTOP' => 'template.1',
                    'PHONES_POSITION' => 'bottom',
                    'MENU_MAIN_POSITION' => 'bottom',
                    'MENU_MAIN_TRANSPARENT' => 'N',
                    'MENU_INFO_SHOW' => 'Y',
                    'SOCIAL_SHOW' => 'Y',
                    'SOCIAL_SHOW_MOBILE' => 'Y',
                    'SOCIAL_POSITION' => 'left',
                    'MOBILE' => 'template.1',
                    'MOBILE_FIXED' => 'N',
                    'MOBILE_FILLED' => 'N',
                    'FIXED' => 'template.1',
                    'FIXED_MENU_POPUP_SHOW' => 'Y',
                    'CATALOG_URL' => '#SITE_DIR#catalog/',
                    'LOGIN_URL' => '#SITE_DIR#personal/profile/',
                    'PROFILE_URL' => '#SITE_DIR#personal/profile/',
                    'PASSWORD_URL' => '#SITE_DIR#personal/profile/?forgot_password=yes',
                    'REGISTER_URL' => '#SITE_DIR#personal/profile/?register=yes',
                    'SEARCH_URL' => '#SITE_DIR#search/',
                    'BASKET_URL' => '#SITE_DIR#personal/basket/',
                    'COMPARE_URL' => '#SITE_DIR#catalog/compare.php',
                    'CONSENT_URL' => '#SITE_DIR#company/consent/',
                    'ORDER_URL' => '#ORDER_PAGE_URL#',
                    'COMPOSITE_FRAME_MODE' => 'A',
                    'COMPOSITE_FRAME_TYPE' => 'AUTO',
                    'SEARCH_CATEGORY_0' => [
                        'no'
                    ],
                    'SEARCH_PRICE_CODE' => [
                        'BASE'
                    ],
                    'SEARCH_PRICE_VAT_INCLUDE' => 'Y',
                    'SEARCH_CURRENCY_CONVERT' => 'Y',
                    'SEARCH_CURRENCY_ID' => 'RUB'
                ]
            ]
        ]]
    ]
];