<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\constructor\models\Build;
use intec\template\Properties;

global $USER;

/** @var Build $oBuild */
$oBuild = $arResult['BUILD'];
$arCategories = $oBuild->getMetaValue('properties-categories');

include(__DIR__.'/modifiers/properties.php');
include(__DIR__.'/modifiers/categories.php');

$arResult['CATEGORY'] = null;

if ($arResult['ACTION'] === 'apply')
    $arResult['CATEGORY'] = Core::$app->request->post('category');

$arCategoriesKeys = ArrayHelper::getKeys($arCategories);
$arResult['CATEGORY'] = ArrayHelper::fromRange(
    $arCategoriesKeys,
    $arResult['CATEGORY']
);

if ($USER->IsAdmin()) {
    if (!empty($arResult['TEMPLATES']))
        $arCategories['templates'] = [
            'name' => Loc::getMessage('C_SYSTEM_SETTINGS_DEFAULT_CATEGORIES_TEMPLATES')
        ];
}

$arResult['CATEGORIES'] = $arCategories;
$arResult['LAZYLOAD'] = [
    'USE' => $arResult['PROPERTIES']['template-images-lazyload-use']['value'],
    'STUB' => Properties::get('template-images-lazyload-stub')
];