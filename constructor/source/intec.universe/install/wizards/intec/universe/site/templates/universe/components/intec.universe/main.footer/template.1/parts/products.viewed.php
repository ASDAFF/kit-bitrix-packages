<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 */

if (!Loader::includeModule('catalog'))
    return;

$sPrefix = 'PRODUCTS_VIEWED_';
$arProducts['PARAMETERS'] = [];

foreach ($arParams as $sKey => $mValue) {
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arProducts['PARAMETERS'][$sKey] = $mValue;
    }
}
?>
<div class="widget-part-products-viewed">
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.products.viewed',
        'tile.1',
        $arProducts['PARAMETERS'],
        $component
    ); ?>
</div>

