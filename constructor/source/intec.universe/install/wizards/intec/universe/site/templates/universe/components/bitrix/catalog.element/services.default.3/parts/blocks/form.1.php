<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-form">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:main.widget',
                'form.5',
                $arBlock['PARAMETERS'],
                $component
            ) ?>
        </div>
    </div>
</div>
