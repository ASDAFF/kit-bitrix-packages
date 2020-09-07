<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'FORM_TEMPLATE' => '.default',
    'FORM_CONSENT' => null,
    'FORM_ORDER_ID' => null,
    'FORM_ORDER_FIELD' => null,
    'FORM_ORDER_TITLE' => null,
    'FORM_FEEDBACK_1_ID' => null,
    'FORM_FEEDBACK_1_FORM_TITLE' => null,
    'FORM_FEEDBACK_1_BLOCK_TITLE' => null,
    'FORM_FEEDBACK_1_BLOCK_DESCRIPTION_SHOW' => 'N',
    'FORM_FEEDBACK_1_BLOCK_DESCRIPTION_TEXT' => null,
    'FORM_FEEDBACK_1_BLOCK_VIEW' => 'left',
    'FORM_FEEDBACK_1_BLOCK_ALIGN_HORIZONTAL' => 'center',
    'FORM_FEEDBACK_1_BLOCK_THEME' => 'dark',
    'FORM_FEEDBACK_1_BLOCK_BG_COLOR' => null,
    'FORM_FEEDBACK_1_BLOCK_BG_IMAGE_SHOW' => 'N',
    'FORM_FEEDBACK_1_BLOCK_BG_IMAGE_PATH' => null,
    'FORM_FEEDBACK_1_BLOCK_BUTTON_TEXT' => null,
    'FORM_FEEDBACK_2_ID' => null,
    'FORM_FEEDBACK_2_FORM_TITLE' => null,
    'FORM_FEEDBACK_2_BLOCK_TITLE' => null,
    'FORM_FEEDBACK_2_BLOCK_DESCRIPTION_SHOW' => 'N',
    'FORM_FEEDBACK_2_BLOCK_DESCRIPTION_TEXT' => null,
    'FORM_FEEDBACK_2_BLOCK_VIEW' => 'left',
    'FORM_FEEDBACK_2_BLOCK_ALIGN_HORIZONTAL' => 'center',
    'FORM_FEEDBACK_2_BLOCK_THEME' => 'dark',
    'FORM_FEEDBACK_2_BLOCK_BG_COLOR' => null,
    'FORM_FEEDBACK_2_BLOCK_BG_IMAGE_SHOW' => 'N',
    'FORM_FEEDBACK_2_BLOCK_BG_IMAGE_PATH' => null,
    'FORM_FEEDBACK_2_BLOCK_BUTTON_TEXT' => null,
    'BLOCKS_IBLOCK_TYPE' => null,
    'BLOCKS_IBLOCK_ID' => null,
    'BLOCKS_PROPERTY_IMAGES' => null,
    'BLOCKS_PROPERTY_TEXT_ADDITIONAL' => null,
    'BLOCKS_PROPERTY_THEME' => null,
    'BLOCKS_PROPERTY_VIEW' => null,
    'PROPERTY_DETAIL_NARROW' => null,
    'PROPERTY_DEFAULT_TEXT_ADDITIONAL_NARROW' => null,
    'PROPERTY_COMPACT_POSITION' => null,
    'BLOCKS_IMAGES_SHOW' => 'N',
    'BLOCKS_BUTTON_TEXT' => null,
    'VIDEO_IBLOCK_TYPE' => null,
    'VIDEO_IBLOCK_ID' => null,
    'VIDEO_PROPERTY_URL' => null,
    'VIDEO_PICTURE_SOURCES' => [],
    'VIDEO_PICTURE_SERVICE_QUALITY' => 'sddefault',
    'LINKS_IBLOCK_TYPE' => null,
    'LINKS_IBLOCK_ID' => null,
    'LINKS_PROPERTY_LINK' => null,
    'LINKS_PROPERTY_NAME' => null,
    'REVIEWS_IBLOCK_TYPE' => null,
    'REVIEWS_IBLOCK_ID' => null,
    'REVIEWS_PROPERTY_POSITION' => null,
    'REVIEWS_POSITION_SHOW' => 'N',
    'REVIEWS_FOOTER_BUTTON_SHOW' => 'N',
    'REVIEW_FOOTER_BUTTON_TEXT' => null,
    'REVIEW_LIST_PAGE_URL' => null,
    'PROPERTY_MARK_HIT' => null,
    'PROPERTY_MARK_NEW' => null,
    'PROPERTY_MARK_RECOMMEND' => null,
    'PROPERTY_TYPE' => null,
    'PROPERTY_PRICE' => null,
    'PROPERTY_PRICE_OLD' => null,
    'PROPERTY_BUTTON_SHOW' => null,
    'PROPERTY_BUTTON_URL' => null,
    'PROPERTY_BUTTON_TEXT' => null,
    'PROPERTY_PICTURE' => null,
    'PROPERTY_CHARACTERISTICS' => [],
    'PROPERTY_ADDITIONAL_TEXT' => null,
    'PROPERTY_ADDITIONAL_TEXT_PICTURE' => null,
    'PROPERTY_PROJECTS' => null,
    'PROPERTY_REVIEWS' => null,
    'PROPERTY_ADVANTAGES' => null,
    'PROPERTY_FAQ' => null,
    'PROPERTY_RECOMMEND' => null,
    'PROPERTY_VIDEO' => null,
    'PROPERTY_TAB_DESCRIPTION_ADVANTAGES_1' => null,
    'PROPERTY_TAB_DESCRIPTION_NEWS_1' => null,
    'PROPERTY_TAB_DESCRIPTION_BLOCKS_1' => null,
    'PROPERTY_TAB_DESCRIPTION_ADVANTAGES_2' => null,
    'PROPERTY_TAB_DESCRIPTION_BLOCKS_2' => null,
    'PROPERTY_TAB_DESCRIPTION_LINKS_1' => null,
    'PROPERTY_TAB_VIDEO' => null,
    'PROPERTY_TAB_INFO' => null,
    'BANNER_BUTTONS_BACK_SHOW' => 'N',
    'BANNER_WIDE' => 'N',
    'BANNER_TYPE_SHOW' => 'N',
    'BANNER_HEADER_H1' => 'N',
    'BANNER_PRICE_SHOW' => 'N',
    'BANNER_ORDER_SHOW' => 'N',
    'BANNER_ORDER_TEXT' => null,
    'BANNER_PICTURE_SHOW' => 'Y',
    'BANNER_CHARACTERISTICS_SHOW' => 'Y',
    'ADDITIONAL_TEXT_SHOW' => 'N',
    'ADDITIONAL_TEXT_DARK' => 'N',
    'ADDITIONAL_TEXT_WIDE' => 'N',
    'TAB_DESCRIPTION_SHOW' => 'N',
    'TAB_DESCRIPTION_NAME' => null,
    'TAB_DESCRIPTION_ADVANTAGES_1_COLUMNS' => 5,
    'TAB_DESCRIPTION_NEWS_1_COLUMNS' => 4,
    'TAB_DESCRIPTION_NEWS_1_DATE_SHOW' => 'N',
    'TAB_DESCRIPTION_NEWS_1_DATE_FORMAT' => null,
    'TAB_DESCRIPTION_NEWS_1_LINK_USE' => 'N',
    'TAB_DESCRIPTION_NEWS_1_LINK_BLANK' => 'N',
    'TAB_DESCRIPTION_LINKS_1_COLUMNS' => 3,
    'TAB_DESCRIPTION_LINKS_1_LINK_BLANK' => 'N',
    'TAB_INFO_SHOW' => 'N',
    'TAB_INFO_NAME' => null,
    'TAB_VIDEO_SHOW' => 'N',
    'TAB_VIDEO_NAME' => null,
    'TAB_VIDEO_COLUMNS' => 3,
    'PROJECTS_SHOW' => 'N',
    'PROJECTS_WIDE' => 'N',
    'PROJECTS_COLUMNS' => 4,
    'REVIEWS_SHOW' => 'N',
    'ADVANTAGES_SHOW' => 'N',
    'ADVANTAGES_COLUMNS' => 4,
    'FAQ_SHOW' => 'N',
    'RECOMMEND_SHOW' => 'N',
    'RECOMMEND_DESCRIPTION' => 'N',
    'VIDEO_SHOW' => 'N',
], $arParams);

$arVisual = [
    'BANNER' => [
        'WIDE' => $arParams['BANNER_WIDE'] === 'Y',
        'BUTTONS' => [
            'BACK' => [
                'SHOW' => $arParams['BANNER_BUTTONS_BACK_SHOW'] === 'Y'
            ]
        ],
        'TYPE' => [
            'SHOW' => $arParams['BANNER_TYPE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TYPE'])
        ],
        'HEADER' => [
            'H1' => $arParams['BANNER_HEADER_H1'] === 'Y'
        ],
        'PRICE' => [
            'SHOW' => $arParams['BANNER_PRICE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PRICE'])
        ],
        'ORDER' => [
            'SHOW' => $arParams['BANNER_ORDER_SHOW'] === 'Y',
            'TEXT' => Html::stripTags($arParams['BANNER_ORDER_TEXT'])
        ],
        'PICTURE' => [
            'SHOW' => $arParams['BANNER_PICTURE_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PICTURE'])
        ],
        'CHARACTERISTICS' => [
            'SHOW' => $arParams['BANNER_CHARACTERISTICS_SHOW'] && !empty($arParams['PROPERTY_CHARACTERISTICS'])
        ]
    ],
    'ADDITIONAL_TEXT' => [
        'SHOW' => $arParams['ADDITIONAL_TEXT_SHOW'] === 'Y' && !empty($arParams['PROPERTY_ADDITIONAL_TEXT']),
        'WIDE' => $arParams['ADDITIONAL_TEXT_WIDE'] === 'Y',
        'DARK' => $arParams['ADDITIONAL_TEXT_DARK'] === 'Y'
    ],
    'TAB' => [
        'DESCRIPTION' => [
            'SHOW' => $arParams['TAB_DESCRIPTION_SHOW'] === 'Y',
            'ADVANTAGES_1' => [
                'COLUMNS' => ArrayHelper::fromRange([5, 4, 6, 7], $arParams['TAB_DESCRIPTION_ADVANTAGES_1_COLUMNS'])
            ],
            'NEWS_1' => [
                'COLUMNS' => ArrayHelper::fromRange([4, 5, 3, 2], $arParams['TAB_DESCRIPTION_NEWS_1_COLUMNS']),
                'DATE' => [
                    'SHOW' => $arParams['TAB_DESCRIPTION_NEWS_1_DATE_SHOW'],
                    'FORMAT' => $arParams['TAB_DESCRIPTION_NEWS_1_DATE_FORMAT']
                ],
                'LINK' => [
                    'USE' => $arParams['TAB_DESCRIPTION_NEWS_1_LINK_USE'],
                    'BLANK' => $arParams['TAB_DESCRIPTION_NEWS_1_LINK_BLANK']
                ]
            ],
            'LINKS_1' => [
                'COLUMNS' => ArrayHelper::fromRange([3, 4], $arParams['TAB_DESCRIPTION_LINKS_1_COLUMNS']),
                'BLANK' => $arParams['TAB_DESCRIPTION_LINKS_1_LINK_BLANK']
            ]
        ],
        'INFO' => [
            'SHOW' => $arParams['TAB_INFO_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TAB_INFO'])
        ],
        'VIDEO' => [
            'SHOW' => $arParams['TAB_VIDEO_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TAB_VIDEO']),
            'COLUMNS' => ArrayHelper::fromRange([3, 4, 5, 2, 1], $arParams['TAB_VIDEO_COLUMNS'])
        ]
    ],
    'PROJECTS' => [
        'SHOW' => $arParams['PROJECTS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_PROJECTS']),
        'WIDE' => $arParams['PROJECTS_WIDE'] === 'Y',
        'COLUMNS' => ArrayHelper::fromRange([4, 3, 5], $arParams['PROJECTS_COLUMNS'])
    ],
    'REVIEWS' => [
        'SHOW' => $arParams['REVIEWS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_REVIEWS']),
    ],
    'ADVANTAGES' => [
        'SHOW' => $arParams['ADVANTAGES_SHOW'] === 'Y' && !empty($arParams['PROPERTY_ADVANTAGES']),
        'COLUMNS' => ArrayHelper::fromRange([4, 5, 3, 2], $arParams['ADVANTAGES_COLUMNS'])
    ],
    'FAQ' => [
        'SHOW' => $arParams['FAQ_SHOW'] === 'Y' && !empty($arParams['PROPERTY_FAQ'])
    ],
    'RECOMMEND' => [
        'SHOW' => $arParams['RECOMMEND_SHOW'] === 'Y' && !empty($arParams['PROPERTY_RECOMMEND'])
    ],
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y' && !empty($arParams['PROPERTY_VIDEO']),
    ]
];

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arResult['DATA'] = [];
$arResult['MARKS'] = [
    'HIT' => $arParams['PROPERTY_MARK_HIT'],
    'NEW' => $arParams['PROPERTY_MARK_NEW'],
    'RECOMMEND' => $arParams['PROPERTY_MARK_RECOMMEND']
];

include(__DIR__.'/modifiers/system/forms.php');
include(__DIR__.'/modifiers/system/blocks.php');
include(__DIR__.'/modifiers/system/video.php');
include(__DIR__.'/modifiers/system/links.php');
include(__DIR__.'/modifiers/system/reviews.php');
include(__DIR__.'/modifiers/system/pictures.php');
include(__DIR__.'/modifiers/blocks/banner.php');
include(__DIR__.'/modifiers/blocks/additional.text.php');
include(__DIR__.'/modifiers/blocks/projects.php');
include(__DIR__.'/modifiers/blocks/reviews.php');
include(__DIR__.'/modifiers/blocks/advantages.php');
include(__DIR__.'/modifiers/blocks/faq.php');
include(__DIR__.'/modifiers/blocks/recommend.php');
include(__DIR__.'/modifiers/blocks/video.php');
include(__DIR__.'/modifiers/tabs/tabs.php');

$this->__component->SetResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);