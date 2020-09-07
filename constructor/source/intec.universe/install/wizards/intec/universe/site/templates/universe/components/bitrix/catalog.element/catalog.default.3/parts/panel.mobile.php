<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-panel-mobile" data-role="panel.mobile">
    <div class="intec-content intec-content-primary">
        <div class="intec-content-wrapper">
            <div class="catalog-element-panel-mobile-content intec-grid intec-grid-a-v-center">
                <div class="intec-grid-item intec-grid-item-shrink-1">
                    <div class="catalog-element-panel-mobile-base">
                        <div class="catalog-element-panel-mobile-name" title="<?= $arResult['NAME'] ?>">
                            <?= $arResult['NAME'] ?>
                        </div>
                    </div>
                </div>
                <div class="intec-grid-item-auto">
                    <?php if ($arResult['ACTION'] !== 'none') { ?>
                        <div class="catalog-element-panel-mobile-purchase">
                            <?php include(__DIR__.'/panel.mobile/purchase.php') ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>