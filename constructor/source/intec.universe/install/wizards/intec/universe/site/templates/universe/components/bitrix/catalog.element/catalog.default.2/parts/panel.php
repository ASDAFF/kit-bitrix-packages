<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-panel" data-role="panel" data-show="false">
    <div class="intec-content intec-content-primary">
        <div class="intec-content-wrapper">
            <div class="catalog-element-panel-content intec-grid intec-grid-a-v-center">
                <div class="intec-grid-item-auto">
                    <div class="catalog-element-panel-picture intec-image">
                        <?php include(__DIR__.'/panel/picture.php') ?>
                    </div>
                </div>
                <div class="intec-grid-item-auto">
                    <div class="catalog-element-panel-base">
                        <div class="catalog-element-panel-name" title="<?= $arResult['NAME'] ?>">
                            <?= $arResult['NAME'] ?>
                        </div>
                        <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                            <div class="catalog-element-panel-quantity-wrap">
                                <?php include(__DIR__.'/panel/quantity.php') ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="intec-grid-item">
                    <div class="intec-grid intec-grid-a-v-center intec-grid-a-h-end">
                        <div class="intec-grid-item-auto">
                            <div class="catalog-element-panel-price">
                                <?php include(__DIR__.'/panel/price.php') ?>
                            </div>
                        </div>
                        <?php if ($arVisual['COUNTER']['SHOW']) { ?>
                            <div class="intec-grid-item-auto">
                                <div class="catalog-element-panel-counter">
                                    <?php include(__DIR__.'/panel/counter.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['ACTION'] !== 'none') { ?>
                            <div class="intec-grid-item-auto">
                                <div class="catalog-element-panel-purchase">
                                    <?php include(__DIR__.'/panel/purchase.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['COMPARE']['USE'] || $arResult['DELAY']['USE']) { ?>
                            <div class="intec-grid-item-auto">
                                <div class="catalog-element-panel-buttons">
                                    <?php include(__DIR__.'/panel/buttons.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>