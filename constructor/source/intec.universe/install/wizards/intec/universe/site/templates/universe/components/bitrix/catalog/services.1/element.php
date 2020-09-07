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

include(__DIR__.'/parts/element/element.php');

?>
<div class="ns-bitrix c-catalog c-catalog-catalog-1 p-element">
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.element',
        $arElement['TEMPLATE'],
        $arElement['PARAMETERS'],
        $component
    ) ?>
</div>