<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);

$arParams = ArrayHelper::merge([
    'DETAIL_MENU_SHOW' => 'Y'
], $arParams);

$arIBlock = $arResult['IBLOCK'];
$arSection = $arResult['SECTION'];

include(__DIR__.'/parts/menu.php');
include(__DIR__.'/parts/element/element.php');

$arMenu['SHOW'] = $arMenu['SHOW'] && $arParams['DETAIL_MENU_SHOW'] === 'Y';
$arColumns = [
    'SHOW' => $arMenu['SHOW'] || ($arFilter['SHOW'] && $arFilter['TYPE'] === 'vertical')
];

if ($arColumns['SHOW']) {
    $arElement['PARAMETERS']['WIDE'] = 'N';
}

?>
<div class="ns-bitrix c-catalog c-catalog-catalog-1 p-element">
    <?php if ($arColumns['SHOW']) { ?>
        <div class="catalog-wrapper intec-content intec-content-visible">
            <div class="catalog-wrapper-2 intec-content-wrapper">
                <div class="catalog-content">
                    <div class="catalog-content-left intec-content-left">
                        <?php if ($arMenu['SHOW']) { ?>
                            <div class="catalog-menu">
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    $arMenu['TEMPLATE'],
                                    $arMenu['PARAMETERS'],
                                    $component
                                ) ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="catalog-content-right intec-content-right">
                        <div class="catalog-content-right-wrapper intec-content-right-wrapper">
    <?php } ?>
    <?php if ($arElement['SHOW']) { ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.element',
            $arElement['TEMPLATE'],
            $arElement['PARAMETERS'],
            $component
        ) ?>
    <?php } ?>
    <?php if ($arColumns['SHOW']) { ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
