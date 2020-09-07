<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

?>
<div class="widget c-widget c-widget-catalog-information-1">
    <div class="widget-content">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '.default',
            [
                'AREA_FILE_SHOW' => 'file',
                'PATH' => $arResult['PATH'],
                'EDIT_TEMPLATE' => null
            ],
            $component
        ); ?>
    </div>
</div>