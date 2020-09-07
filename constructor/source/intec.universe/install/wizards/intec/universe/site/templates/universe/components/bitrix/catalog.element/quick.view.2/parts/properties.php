<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

?>
<div class="catalog-element-properties intec-grid intec-grid-wrap intec-grid-i-h-25">
    <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $arProperty) { ?>
        <?php if (!empty($arProperty['USER_TYPE'])) continue ?>
        <div class="catalog-element-property intec-grid-item-2">
            <div class="catalog-element-property-text">
                <span class="catalog-element-property-decoration intec-cl-background"></span>
                <span class="catalog-element-property-value">
                    <?= $arProperty['NAME'].' - '.(!Type::isArray($arProperty['DISPLAY_VALUE']) ?
                        $arProperty['DISPLAY_VALUE'] :
                        implode(', ', $arProperty['DISPLAY_VALUE'])
                    ) ?>
                </span>
            </div>
        </div>
    <?php } ?>
</div>