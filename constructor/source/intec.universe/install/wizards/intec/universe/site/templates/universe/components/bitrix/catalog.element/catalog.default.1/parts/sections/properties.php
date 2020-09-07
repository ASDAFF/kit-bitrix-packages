<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-section-properties">
    <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) { ?>
        <div class="catalog-element-section-property">
            <div class="catalog-element-section-property-name">
                <?= $arProperty['NAME'] ?>
            </div>
            <div class="catalog-element-section-property-value">
                <?= !Type::isArray($arProperty['DISPLAY_VALUE']) ?
                    $arProperty['DISPLAY_VALUE'] :
                    implode(', ', $arProperty['DISPLAY_VALUE'])
                ?>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php } ?>
</div>