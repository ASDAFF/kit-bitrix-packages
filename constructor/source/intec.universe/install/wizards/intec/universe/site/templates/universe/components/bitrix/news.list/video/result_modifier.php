<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_DURATION' => null
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'DURATION' => $arParams['PROPERTY_DURATION']
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arElement = [];
foreach ($arResult['ITEMS'] as $el){
    if(empty($arParams['IBLOCK_DISPLAY_VIDEO']) || in_array($el['ID'], $arParams['IBLOCK_DISPLAY_VIDEO'])) {
        if ($el['ID'] == $arParams['IBLOCK_FIRST_VIDEO'])
            array_unshift($arElement, $el);
        else
            $arElement[] = $el;
    }
}
$arResult['ITEMS'] = $arElement;