<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var string $componentName
 * @var string $templateName
 * @var string $siteTemplate
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['INPUT_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_INPUT_ID'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['TIPS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SEARCH_TITLE_POPUP_1_TIPS_USE'),
    'TYPE' => 'CHECKBOX'
];

if (Loader::includeModule('catalog')) {
    include(__DIR__.'/parameters/base.php');
} else if (Loader::includeModule('intec.startshop')) {
    include(__DIR__.'/parameters/lite.php');
}