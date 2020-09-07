<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock') || !Loader::includeModule('intec.core'))
    return;

$iIBlockId = $arCurrentValues['IBLOCK_ID'];
$iIBlockIdReviews = $arCurrentValues['IBLOCK_ID_REVIEWS'];

$arIBlocksTypes = array(
    '' => ''
);

$arIBlocksTypes = array_merge(
    $arIBlocksTypes,
    CIBlockParameters::GetIBlockTypes()
);

$arIBlocks = array();
$arIBlocksFilter = array();
$arIBlocksFilter['ACTIVE'] = 'Y';

$rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), $arIBlocksFilter);

while ($arIBlock = $rsIBlocks->Fetch())
    $arIBlocks[$arIBlock['ID']] = $arIBlock;

$getIBlocksByType = function ($sType = null) use ($arIBlocks) {
    $arResult = array();

    foreach ($arIBlocks as $arIBlock) {
        $sName = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];

        if ($arIBlock['IBLOCK_TYPE_ID'] == $sType || $sType == null)
            $arResult[$arIBlock['ID']] = $sName;
    }

    return $arResult;
};

$arForms = array();
$arFormFields = array();

if (Loader::includeModule('form')) {
    require(__DIR__.'/parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    require(__DIR__.'/parameters/lite.php');
}

$arTemplateParameters = array(
    'DISPLAY_LIST_TAB_ALL' => array(
        'PARENT' => 'LIST_SETTINGS',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_DISPLAY_TAB_ALL')
    ),
    'IBLOCK_TYPE_SERVICES' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_IBLOCK_TYPE_SERVICES'),
        'VALUES' => $arIBlocksTypes,
        'REFRESH' => 'Y'
    ),
    'IBLOCK_ID_SERVICES' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_IBLOCK_ID_SERVICES'),
        'VALUES' => $getIBlocksByType($arCurrentValues['IBLOCK_TYPE_SERVICES']),
        'ADDITIONAL_VALUES' => 'Y'
    ),
    'ALLOW_LINK_SERVICES' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_ALLOW_LINK_SERVICES')
    ),
    'DETAIL_URL_SERVICES' => CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_URL',
        Loc::getMessage('N_PROJECTS_PARAMETERS_DETAIL_URL_SERVICES'),
        '',
        'URL_TEMPLATES'
    ),
    'IBLOCK_TYPE_REVIEWS' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_IBLOCK_TYPE_REVIEWS'),
        'VALUES' => $arIBlocksTypes,
        'REFRESH' => 'Y'
    ),
    'IBLOCK_ID_REVIEWS' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_IBLOCK_ID_REVIEWS'),
        'VALUES' => $getIBlocksByType($arCurrentValues['IBLOCK_TYPE_REVIEWS']),
        'ADDITIONAL_VALUES' => 'Y'
    ),
    'ALLOW_LINK_REVIEWS' => array(
        'PARENT' => 'BASE',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_ALLOW_LINK_REVIEWS')
    ),
    'PAGE_URL_REVIEWS' => array(
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PAGE_URL_REVIEWS')
    ),
    'DETAIL_URL_REVIEWS' => CIBlockParameters::GetPathTemplateParam(
        'DETAIL',
        'DETAIL_URL',
        Loc::getMessage('N_PROJECTS_PARAMETERS_DETAIL_URL_REVIEWS'),
        '',
        'URL_TEMPLATES'
    ),
    'DISPLAY_FORM_ORDER' => array(
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_DISPLAY_FORM_ORDER'),
        'REFRESH' => 'Y'
    ),
    'DISPLAY_FORM_ASK' => array(
        'PARENT' => 'VISUAL',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_DISPLAY_FORM_ASK'),
        'REFRESH' => 'Y'
    ),
    'CONSENT_URL' => array(
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_CONSENT_URL')
    )
);

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LIST_LAZYLOAD_USE'] = [
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DETAIL_LAZYLOAD_USE'] = [
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['DISPLAY_FORM_ORDER'] == 'Y') {
    $arTemplateParameters['FORM_ORDER'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_FORM_ORDER'),
        'VALUES' => $arForms,
        'REFRESH' => 'Y'
    );

    if (!empty($arCurrentValues['FORM_ORDER'])) {
        $arTemplateParameters['PROPERTY_FORM_ORDER_PROJECT'] = array(
            'PARENT' => 'DATA_SOURCE',
            'TYPE' => 'LIST',
            'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_FORM_ORDER_PROJECT'),
            'VALUES' => $arFormFields,
            'ADDITIONAL_VALUES' => 'Y'
        );
    }
}

if ($arCurrentValues['DISPLAY_FORM_ASK'] == 'Y') {
    $arTemplateParameters['FORM_ASK'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_FORM_ASK'),
        'VALUES' => $arForms
    );
}

if (!empty($iIBlockId)) {
    $arProperties = array();
    $arPropertiesFiles = array();
    $arPropertiesFile = array();
    $arPropertiesString = array();
    $arPropertiesLink = array();
    $arPropertiesForDescription = array();
    $rsProperties = CIBlockProperty::GetList(array('SORT' => 'ASC'), array(
        'IBLOCK_ID' => $iIBlockId,
        'ACTIVE' => 'Y'
    ));

    while ($arProperty = $rsProperties->GetNext()) {
        $sCode = $arProperty['CODE'];

        if (empty($sCode))
            continue;

        $sName = '['.$arProperty['CODE'].'] '.$arProperty['NAME'];

        if ($arProperty['MULTIPLE'] != 'Y') {
            if (($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['USER_TYPE'] != 'HTML') || $arProperty['PROPERTY_TYPE'] == 'L')
                $arPropertiesForDescription[$sCode] = $sName;

            if ($arProperty['PROPERTY_TYPE'] == 'S')
                $arPropertiesString[$sCode] = $sName;

            if ($arProperty['PROPERTY_TYPE'] == 'F')
                $arPropertiesFile[$sCode] = $sName;
        } else {
            if ($arProperty['PROPERTY_TYPE'] == 'F')
                $arPropertiesFiles[$sCode] = $sName;
        }

        if ($arProperty['PROPERTY_TYPE'] == 'E')
            $arPropertiesLink[$sCode] = $sName;

        $arProperties[$arProperty['CODE']] = $arProperty;
    }

    $arTemplateParameters['DESCRIPTION_DETAIL_PROPERTIES'] = array(
        'PARENT' => 'DETAIL_SETTINGS',
        'TYPE' => 'LIST',
        'MULTIPLE' => 'Y',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_DESCRIPTION_PROPERTIES'),
        'VALUES' => $arPropertiesForDescription
    );

    $arTemplateParameters['PROPERTY_GALLERY'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_GALLERY'),
        'VALUES' => $arPropertiesFiles
    );

    $arTemplateParameters['PROPERTY_OBJECTIVE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_OBJECTIVE'),
        'VALUES' => $arPropertiesString
    );

    $arTemplateParameters['PROPERTY_SERVICES'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_SERVICES'),
        'VALUES' => $arPropertiesLink
    );

    $arTemplateParameters['PROPERTY_IMAGES'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_IMAGES'),
        'VALUES' => $arPropertiesFiles
    );

    $arTemplateParameters['PROPERTY_SOLUTION_BEGIN'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_SOLUTION_BEGIN'),
        'VALUES' => $arPropertiesString
    );

    $arTemplateParameters['PROPERTY_SOLUTION_IMAGE'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_SOLUTION_IMAGE'),
        'VALUES' => $arPropertiesFile
    );

    $arTemplateParameters['PROPERTY_SOLUTION_END'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_SOLUTION_END'),
        'VALUES' => $arPropertiesString
    );
}

if (!empty($iIBlockIdReviews)) {
    $arProperties = array();
    $arPropertiesLink = array();
    $rsProperties = CIBlockProperty::GetList(array('SORT' => 'ASC'), array(
        'IBLOCK_ID' => $iIBlockIdReviews,
        'ACTIVE' => 'Y'
    ));

    while ($arProperty = $rsProperties->GetNext()) {
        $sCode = $arProperty['CODE'];

        if (empty($sCode))
            continue;

        $sName = '['.$arProperty['CODE'].'] '.$arProperty['NAME'];

        if ($arProperty['MULTIPLE'] != 'Y')
            if ($arProperty['PROPERTY_TYPE'] == 'E')
                $arPropertiesLink[$sCode] = $sName;

        $arProperties[$arProperty['CODE']] = $arProperty;
    }

    $arTemplateParameters['PROPERTY_REVIEW'] = array(
        'PARENT' => 'DATA_SOURCE',
        'TYPE' => 'LIST',
        'NAME' => Loc::getMessage('N_PROJECTS_PARAMETERS_PROPERTY_REVIEW'),
        'VALUES' => $arPropertiesLink
    );
}

$arTemplateParameters['LIST_TEMPLATE'] = array(
    'PARENT' => 'LIST_SETTINGS',
    'TYPE' => 'LIST',
    'NAME' => Loc::getMessage('C_NEWS_LIST_TEMPLATE'),
    'VALUES' => array(
        '.default' => Loc::getMessage('C_NEWS_LIST_TEMPLATE_DEFAULT'),
        'projects.list' => Loc::getMessage('C_NEWS_LIST_TEMPLATE_LIST')
    ),
    "DEFAULT" => '.default',
    'ADDITIONAL_VALUES' => 'N',
    'REFRESH' => 'Y'
);
if ($arCurrentValues['LIST_TEMPLATE'] == 'projects.list') {
    $arTemplateParameters['LIST_DESCRIPTION_DISPLAY'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('C_NEWS_LIST_DESCRIPTION_DISPLAY'),
        'REFRESH' => 'Y',
        "DEFAULT" => 'Y'
    );
    $arTemplateParameters['LIST_PICTURE_DISPLAY'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('C_NEWS_LIST_PICTURE_DISPLAY'),
        'REFRESH' => 'Y',
        "DEFAULT" => 'Y'
    );
}

include(__DIR__.'/parameters/regionality.php');

$arTemplateParameters['USE_SEARCH'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['USE_RSS'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['NUM_NEWS'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['NUM_DAYS'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['YANDEX'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['USE_RATING'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['MAX_VOTE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['VOTE_NAMES'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['USE_CATEGORIES'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['CATEGORY_IBLOCK'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['CATEGORY_CODE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['CATEGORY_ITEMS_COUNT'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['USE_REVIEW'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['MESSAGES_PER_PAGE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['USE_CAPTCHA'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['REVIEW_AJAX_POST'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['PATH_TO_SMILE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['FORUM_ID'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['URL_TEMPLATES_READ'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['SHOW_LINK_TO_FORUM'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['USE_FILTER'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['FILTER_NAME'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['FILTER_FIELD_CODE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['FILTER_PROPERTY_CODE'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['PREVIEW_TRUNCATE_LEN'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['LIST_ACTIVE_DATE_FORMAT'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['LIST_FIELD_CODE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['HIDE_LINK_WHEN_NO_DETAIL'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['DETAIL_ACTIVE_DATE_FORMAT'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['DETAIL_FIELD_CODE'] = ['HIDDEN' => 'Y'];

$arTemplateParameters['DETAIL_DISPLAY_TOP_PAGER'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['DETAIL_DISPLAY_BOTTOM_PAGER'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['DETAIL_PAGER_TITLE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['DETAIL_PAGER_TEMPLATE'] = ['HIDDEN' => 'Y'];
$arTemplateParameters['DETAIL_PAGER_SHOW_ALL'] = ['HIDDEN' => 'Y'];