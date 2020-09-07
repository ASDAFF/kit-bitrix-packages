<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arForms
 * @var array $rsTemplates
 */

$sFormName = null;

if (Loader::includeModule('intec.startshop') && !Loader::includeModule('catalog')) {
    include('parameters/lite.php');
} elseif (Loader::includeModule('form')) {
    include('parameters/base.php');
} else
    return;

$arTemplates = array();

foreach ($rsTemplates as $arTemplate) {
    $arTemplates[$arTemplate['NAME']] = $arTemplate['NAME'].(!empty($arTemplate['TEMPLATE']) ? ' ('.$arTemplate['TEMPLATE'].')' : null);
}

$arComponentParameters = array(
    'GROUPS' => array(),
    'PARAMETERS' => array(
        'ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('LANDING_FORM_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arForms,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ),
        'TEMPLATE' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('LANDING_FORM_TEMPLATE'),
            'TYPE' => 'LIST',
            'VALUES' => $arTemplates,
            'DEFAULT' => '.default'
        ),
        'CONSENT' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('LANDING_FORM_CONSENT'),
            'TYPE' => 'STRING'
        ),
        'NAME' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('LANDING_FORM_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => $sFormName
        ),
        'CACHE_TIME' => array()
    )
);