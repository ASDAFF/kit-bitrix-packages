<?php return [
    'code' => 'footer',
    'name' => 'Подвал',
    'sort' => 200,
    'container' => [
        'containers' => [[
            'code' => 'footer-blocks.form',
            'order' => 0,
            'variator' => [
                'variants' => [[
                    'code' => 'wide.1',
                    'name' => 'Широкая 1',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:widget',
                                'template' => 'web.form.2',
                                'properties' => [
                                    'GRAB_DATA' => 'N',
                                    'WEB_FORM_ID' => '#FORMS_FEEDBACK_ID#',
                                    'WEB_FORM_TEMPLATE' => '.default',
                                    'CACHE_TYPE' => 'A',
                                    'CACHE_TIME' => 3600000,
                                    'CONSENT_URL' => '#SITE_DIR#company/consent/'
                                ]
                            ]
                        ]]
                    ]
                ], [
                    'code' => 'wide.2',
                    'name' => 'Широкая 2',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:main.widget',
                                'template' => 'form.3',
                                'properties' => [
                                    'WEB_FORM_ID' => '#FORMS_QUESTION_ID#',
                                    'WEB_FORM_CONSENT_LINK' => '#SITE_DIR#company/consent/',
                                    'WEB_FORM_TITLE_SHOW' => 'Y',
                                    'WEB_FORM_TITLE_POSITION' => 'center',
                                    'WEB_FORM_DESCRIPTION_SHOW' => 'Y',
                                    'WEB_FORM_DESCRIPTION_POSITION' => 'center',
                                    'WEB_FORM_THEME' => 'light',
                                    'WEB_FORM_BACKGROUND_USE' => 'Y',
                                    'WEB_FORM_BACKGROUND_COLOR' => 'theme',
                                    'WEB_FORM_CONSENT_SHOW' => 'parameters',
                                    'CACHE_TYPE' => 'A',
                                    'CACHE_TIME' => 3600000
                                ]
                            ]
                        ]]
                    ]
                ]]
            ]
        ], [
            'code' => 'footer-blocks.contacts',
            'condition' => [
                'type' => 'group',
                'operator' => 'and',
                'result' => 1,
                'conditions' => [[
                    'type' => 'match',
                    'match' => 'path',
                    'value' => '^\\/contacts\\/',
                    'result' => 0
                ]]
            ],
            'order' => 1,
            'variator' => [
                'variants' => [[
                    'code' => 'wide.1',
                    'name' => 'Широкие 1',
                    'container' => [
                        'containers' => [[
                            'component' => [
                                'code' => 'intec.universe:main.widget',
                                'template' => 'contacts.1',
                                'properties' => [
                                    'IBLOCK_TYPE' => '#CONTENT_CONTACTS_IBLOCK_TYPE#',
                                    'IBLOCK_ID' => '#CONTENT_CONTACTS_IBLOCK_ID#',
                                    'SETTINGS_USE' => 'Y',
                                    'NEWS_COUNT' => '20',
                                    'MAIN' => '#CONTENT_CONTACTS_CONTACT_ID#',
                                    'PROPERTY_CODE' => [
                                        'ADDRESS',
                                        'CITY',
                                        'PHONE',
                                        'MAP'
                                    ],
                                    'PROPERTY_ADDRESS' => 'ADDRESS',
                                    'PROPERTY_CITY' => 'CITY',
                                    'PROPERTY_PHONE' => 'PHONE',
                                    'PROPERTY_MAP' => 'MAP',
                                    'MAP_VENDOR' => 'yandex',
                                    'WEB_FORM_TEMPLATE' => '.default',
                                    'WEB_FORM_ID' => '#FORMS_FEEDBACK_ID#',
                                    'WEB_FORM_NAME' => 'Задать вопрос',
                                    'WEB_FORM_CONSENT_URL' => '#SITE_DIR#company/consent/',
                                    'FEEDBACK_BUTTON_TEXT' => 'Написать',
                                    'FEEDBACK_TEXT' => 'Связаться с руководителем',
                                    'FEEDBACK_IMAGE' => '#TEMPLATE_PATH#/images/face.png',
                                    'ADDRESS_SHOW' => 'Y',
                                    'PHONE_SHOW' => 'Y',
                                    'SHOW_FORM' => 'Y',
                                    'FEEDBACK_TEXT_SHOW' => 'Y',
                                    'FEEDBACK_IMAGE_SHOW' => 'Y',
                                    'CACHE_TYPE' => 'A',
                                    'CACHE_TIME' => 3600000
                                ]
                            ]
                        ]]
                    ]
                ]]
            ]
        ], [
            'code' => 'component',
            'order' => 2,
            'component' => [
                'code' => 'intec.universe:main.footer',
                'template' => 'template.1',
                'properties' => [
                    'SETTINGS_USE' => 'Y',
                    'PRODUCTS_VIEWED_SHOW' => 'Y',
                    'PRODUCTS_VIEWED_LAZYLOAD_USE' => 'N',
                    'REGIONALITY_USE' => 'N',
                    'CONTACTS_USE' => 'Y',
                    'CONTACTS_IBLOCK_TYPE' => '#CONTENT_CONTACTS_IBLOCK_TYPE#',
                    'CONTACTS_IBLOCK_ID' => '#CONTENT_CONTACTS_IBLOCK_ID#',
                    'CONTACTS_REGIONALITY_USE' => 'Y',
                    'CONTACTS_REGIONALITY_STRICT' => 'N',
                    'MENU_MAIN_ROOT' => 'bottom',
                    'MENU_MAIN_CHILD' => 'left',
                    'MENU_MAIN_LEVEL' => 4,
                    'SEARCH_NUM_CATEGORIES' => 1,
                    'SEARCH_TOP_COUNT' => 5,
                    'SEARCH_ORDER' => 'date',
                    'SEARCH_USE_LANGUAGE_GUESS' => 'Y',
                    'SEARCH_CHECK_DATES' => 'N',
                    'SEARCH_SHOW_OTHERS' => 'N',
                    'SEARCH_INPUT_ID' => 'footer-search',
                    'SEARCH_TIPS_USE' => 'N',
                    'SEARCH_MODE' => 'site',
                    'LOGOTYPE_PATH' => '#SITE_DIR#include/logotype.php',
                    'CONTACTS_PROPERTY_CITY' => 'CITY',
                    'CONTACTS_PROPERTY_ADDRESS' => 'ADDRESS',
                    'CONTACTS_PROPERTY_PHONE' => 'PHONE',
                    'CONTACTS_PROPERTY_EMAIL' => 'EMAIL',
                    'CONTACTS_PROPERTY_REGION' => 'REGIONS',
                    'PHONE_VALUE' => '#SITE_PHONE#',
                    'PRODUCTS_VIEWED_IBLOCK_MODE' => 'multi',
                    'ADDRESS_VALUE' => '#SITE_ADDRESS#',
                    'EMAIL_VALUE' => '#SITE_MAIL#',
                    'COPYRIGHT_VALUE' => '&copy; #YEAR# Universe, Все права защищены',
                    'FORMS_CALL_ID' => '#FORMS_CALL_ID#',
                    'FORMS_CALL_TEMPLATE' => '.default',
                    'SOCIAL_VK_LINK' => 'https://vk.com',
                    'SOCIAL_FACEBOOK_LINK' => 'https://facebook.com',
                    'SOCIAL_INSTAGRAM_LINK' => 'https://instagram.com',
                    'SOCIAL_TWITTER_LINK' => 'https://twitter.com',
                    'LOGOTYPE_SHOW' => 'Y',
                    'PHONE_SHOW' => 'Y',
                    'PRODUCTS_VIEWED_TITLE_SHOW' => 'Y',
                    'PRODUCTS_VIEWED_TITLE' => 'Ранее вы смотрели',
                    'PRODUCTS_VIEWED_PAGE_ELEMENT_COUNT' => '10',
                    'PRODUCTS_VIEWED_COLUMNS' => 4,
                    'PRODUCTS_VIEWED_SHOW_NAVIGATION' => 'Y',
                    'ADDRESS_SHOW' => 'Y',
                    'EMAIL_SHOW' => 'Y',
                    'COPYRIGHT_SHOW' => 'Y',
                    'FORMS_CALL_SHOW' => 'Y',
                    'FORMS_CALL_TITLE' => 'Заказать звонок',
                    'MENU_MAIN_SHOW' => 'Y',
                    'SEARCH_SHOW' => 'Y',
                    'SOCIAL_SHOW' => 'Y',
                    'THEME' => 'dark',
                    'TEMPLATE' => 'template.1',
                    'ICONS' => [
                        'ALFABANK',
                        'SBERBANK',
                        'QIWI',
                        'YANDEXMONEY',
                        'VISA',
                        'MASTERCARD'
                    ],
                    'CONSENT_URL' => '#SITE_DIR#company/consent/',
                    'CATALOG_URL' => '#SITE_DIR#catalog/',
                    'SEARCH_URL' => '#SITE_DIR#search/',
                    'PRODUCTS_VIEWED_PRICE_CODE' => [
                        'BASE'
                    ],
                    'SEARCH_CATEGORY_0' => [
                        'no'
                    ],
                    'SEARCH_PRICE_CODE' => [
                        'BASE'
                    ],
                    'SEARCH_PRICE_VAT_INCLUDE' => 'Y',
                    'SEARCH_CURRENCY_CONVERT' => 'N'
                ]
            ]
        ]]
    ]
];