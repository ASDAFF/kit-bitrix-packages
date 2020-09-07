<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 * @var array $arParts
 * @var InnerTemplate $desktopTemplate
 * @var InnerTemplate $fixedTemplate
 * @var InnerTemplate $mobileTemplate
 */

$sComponent = 'bitrix:menu';
$sTemplate = 'popup.';

$arTemplates = Arrays::from(CComponentUtil::GetTemplatesList(
    $sComponent,
    $siteTemplate
))->asArray(function ($iIndex, $arTemplate) use (&$sTemplate) {
    if (!StringHelper::startsWith($arTemplate['NAME'], $sTemplate))
        return ['skip' => true];

    $sName = StringHelper::cut(
        $arTemplate['NAME'],
        StringHelper::length($sTemplate)
    );

    return [
        'key' => $sName,
        'value' => $sName
    ];
});

$sPrefix = 'MENU_POPUP_';
$sTemplate = ArrayHelper::getValue($arCurrentValues, $sPrefix.'TEMPLATE');
$sTemplate = ArrayHelper::fromRange($arTemplates, $sTemplate, false, false);

if (!empty($sTemplate))
    $sTemplate = 'popup.'.$sTemplate;

$arTemplateParameters['MENU_POPUP_TEMPLATE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_HEADER_TEMP1_'.$sPrefix.'TEMPLATE'),
    'TYPE' => 'LIST',
    'VALUES' => $arTemplates,
    'REFRESH' => 'Y'
];

if (!empty($sTemplate)) {
    $arTemplateParameters = ArrayHelper::merge($arTemplateParameters, Component::getParameters(
        $sComponent,
        $sTemplate,
        $siteTemplate,
        $arCurrentValues,
        $sPrefix,
        function ($sKey, &$arParameter) use (&$sPrefix) {
            if (
                StringHelper::startsWith($sKey, 'SEARCH_') ||
                StringHelper::startsWith($sKey, 'LOGOTYPE')
            ) return false;

            if (ArrayHelper::isIn($sKey, [
                'CATALOG_LINKS',

                'CONTACTS_ADVANCED',
                'CONTACTS_ADDRESS',
                'CONTACTS_PHONE',
                'CONTACTS_EMAIL',
                'CONTACTS_SCHEDULE',
                'CONTACTS_CITY',

                'FORMS_CALL_SHOW',
                'FORMS_CALL_ID',
                'FORMS_CALL_TEMPLATE',
                'FORMS_CALL_TITLE',

                'COMPARE_IBLOCK_TYPE',
                'COMPARE_IBLOCK_ID',
                'COMPARE_CODE',
                'COMPARE_URL',

                'SOCIAL_VK_LINK',
                'SOCIAL_INSTAGRAM_LINK',
                'SOCIAL_FACEBOOK_LINK',
                'SOCIAL_TWITTER_LINK',

                'CONSENT_URL',
                'BASKET_URL',
                'ORDER_URL',
                'LOGIN_URL',
                'PROFILE_URL',
                'PASSWORD_URL',
                'REGISTER_URL'
            ])) return false;

            $arParameter['NAME'] = Loc::getMessage('C_HEADER_TEMP1_MENU_POPUP').'. '.$arParameter['NAME'];

            return true;
        },
        Component::PARAMETERS_MODE_TEMPLATE
    ));
}