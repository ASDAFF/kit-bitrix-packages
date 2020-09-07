<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::IncludeModule('iblock'))
    return;

$sIBlockType = $arCurrentValues['IBLOCK_TYPE'];
$iIBlockId = $arCurrentValues['IBLOCK_ID'];

$arTemplateParameters = array(
    'LAZYLOAD_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ),
    'SHOW_MAP' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_SHOW_MAP'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'MAP_VENDOR' => array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_MAP_VENDOR'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'google' => Loc::getMessage('C_NEWS_STORE_1_MAP_VENDOR_GOOGLE'),
            'yandex' => Loc::getMessage('C_NEWS_STORE_1_MAP_VENDOR_YANDEX'),
        ),
        'ADDITIONAL_VALUES' => 'N',
        'DEFAULT' => 'google'
    ),
    'PHONE_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_PHONE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'SCHEDULE_SHOW' => array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_SCHEDULE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'REFRESH' => 'Y'
    ),
    'SETTINGS_USE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    )
);

if (!empty($iIBlockId)) {
    $arPropertiesString = array();
    $arPropertiesMap = array();

    $arIBlockProperties = array();
    $rsIBlockProperties = CIBlockProperty::GetList(array(
        'SORT' => 'ASC'
    ), array(
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $iIBlockId,
        'IBLOCK_TYPE' => $sIBlockType
    ));

    while ($arIBlockProperty = $rsIBlockProperties->Fetch()) {
        if (empty($arIBlockProperty['CODE']))
            continue;

        $arIBlockProperties[$arIBlockProperty['CODE']] = $arIBlockProperty;

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S')
            $arPropertiesString[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];

        if ($arIBlockProperty['PROPERTY_TYPE'] == 'S' && ($arIBlockProperty['USER_TYPE'] == 'map_google' || $arIBlockProperty['USER_TYPE'] == 'map_yandex'))
            $arPropertiesMap[$arIBlockProperty['CODE']] = '['.$arIBlockProperty['CODE'].'] '.$arIBlockProperty['NAME'];
    }

    if ($arCurrentValues['SHOW_MAP'] == 'Y') {
        $arTemplateParameters['PROPERTY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_STORE_1_PROPERTY_MAP'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertiesMap,
            'ADDITIONAL_VALUES' => 'Y'
        );

        $arTemplateParameters['API_KEY_MAP'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_NEWS_STORE_1_API_KEY_MAP'),
            'TYPE' => 'STRING'
        );
    }

    $arTemplateParameters['PROPERTY_ADDRESS'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_PROPERTY_ADDRESS'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_PHONE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_PROPERTY_PHONE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_EMAIL'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_PROPERTY_EMAIL'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );

    $arTemplateParameters['PROPERTY_SCHEDULE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_NEWS_STORE_1_PROPERTY_SCHEDULE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertiesString,
        'ADDITIONAL_VALUES' => 'Y'
    );
}



$arTemplateParameters['USE_SEARCH']['HIDDEN'] = 'Y';

$arTemplateParameters['USE_RSS']['HIDDEN'] = 'Y';
$arTemplateParameters['NUM_NEWS']['HIDDEN'] = 'Y';
$arTemplateParameters['NUM_DAYS']['HIDDEN'] = 'Y';
$arTemplateParameters['YANDEX']['HIDDEN'] = 'Y';

$arTemplateParameters['USE_RATING']['HIDDEN'] = 'Y';
$arTemplateParameters['MAX_VOTE']['HIDDEN'] = 'Y';
$arTemplateParameters['VOTE_NAMES']['HIDDEN'] = 'Y';

$arTemplateParameters['USE_CATEGORIES']['HIDDEN'] = 'Y';
$arTemplateParameters['CATEGORY_IBLOCK']['HIDDEN'] = 'Y';
$arTemplateParameters['CATEGORY_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters['CATEGORY_ITEMS_COUNT']['HIDDEN'] = 'Y';

$arTemplateParameters['USE_REVIEW']['HIDDEN'] = 'Y';
$arTemplateParameters['MESSAGES_PER_PAGE']['HIDDEN'] = 'Y';
$arTemplateParameters['USE_CAPTCHA']['HIDDEN'] = 'Y';
$arTemplateParameters['REVIEW_AJAX_POST']['HIDDEN'] = 'Y';
$arTemplateParameters['PATH_TO_SMILE']['HIDDEN'] = 'Y';
$arTemplateParameters['FORUM_ID']['HIDDEN'] = 'Y';
$arTemplateParameters['URL_TEMPLATES_READ']['HIDDEN'] = 'Y';
$arTemplateParameters['SHOW_LINK_TO_FORUM']['HIDDEN'] = 'Y';

$arTemplateParameters['USE_FILTER']['HIDDEN'] = 'Y';
$arTemplateParameters['FILTER_NAME']['HIDDEN'] = 'Y';
$arTemplateParameters['FILTER_FIELD_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters['FILTER_PROPERTY_CODE']['HIDDEN'] = 'Y';

$arTemplateParameters['PREVIEW_TRUNCATE_LEN']['HIDDEN'] = 'Y';
$arTemplateParameters['LIST_ACTIVE_DATE_FORMAT']['HIDDEN'] = 'Y';
$arTemplateParameters['LIST_FIELD_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters['HIDE_LINK_WHEN_NO_DETAIL']['HIDDEN'] = 'Y';

$arTemplateParameters['DETAIL_ACTIVE_DATE_FORMAT']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_FIELD_CODE']['HIDDEN'] = 'Y';

$arTemplateParameters['DETAIL_DISPLAY_TOP_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_DISPLAY_BOTTOM_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_PAGER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_PAGER_TEMPLATE']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_PAGER_SHOW_ALL']['HIDDEN'] = 'Y';