<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<div class="catalog-element-section-services">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.services',
        'template.17',
        $arResult['SERVICES']['PARAMETERS'],
        $component
    ) ?>
</div>