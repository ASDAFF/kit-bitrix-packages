<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplate
 * @var IntecBasketLiteComponent $component
 * @var CBitrixComponentTemplate $this
 */

?>
<div class="sale-basket-small-panel intec-content-wrap intec-cl-background-dark">
    <div class="sale-basket-small-panel-wrapper intec-grid intec-grid-nowrap">
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <?= Html::beginTag('a', [
                'class' => 'sale-basket-small-panel-button intec-grid-item',
                'href' => $arResult['URL']['BASKET']
            ]) ?>
            <div class="sale-basket-small-panel-button-wrapper">
                <div class="sale-basket-small-panel-button-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-cart"></i>
                </div>
                <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-panel-button-counter intec-cl-background">
                        <?= $arResult['BASKET']['COUNT'] ?>
                    </div>
                <?php } ?>
            </div>
            <?= Html::endTag('a') ?>
        <?php } ?>
        <?php if ($arResult['DELAYED']['SHOW']) { ?>
            <?= Html::beginTag('a', [
                'class' => 'sale-basket-small-panel-button intec-grid-item',
                'href' => $arResult['URL']['DELAYED']
            ]) ?>
            <div class="sale-basket-small-panel-button-wrapper">
                <div class="sale-basket-small-panel-button-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-heart"></i>
                </div>
                <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-panel-button-counter intec-cl-background">
                        <?= $arResult['DELAYED']['COUNT'] ?>
                    </div>
                <?php } ?>
            </div>
            <?= Html::endTag('a') ?>
        <?php } ?>
        <?php if ($arResult['COMPARE']['SHOW']) { ?>
            <?= Html::beginTag('a', [
                'class' => 'sale-basket-small-panel-button intec-grid-item',
                'href' => $arResult['URL']['COMPARE']
            ]) ?>
            <div class="sale-basket-small-panel-button-wrapper">
                <div class="sale-basket-small-panel-button-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-compare"></i>
                </div>
                <?php if ($arResult['COMPARE']['COUNT'] > 0) { ?>
                    <div class="sale-basket-small-panel-button-counter intec-cl-background">
                        <?= $arResult['COMPARE']['COUNT'] ?>
                    </div>
                <?php } ?>
            </div>
            <?= Html::endTag('a') ?>
        <?php } ?>
        <?php if ($arResult['FORM']['SHOW']) { ?>
            <div data-role="button" data-action="form" class="sale-basket-small-panel-button intec-grid-item">
                <div class="sale-basket-small-panel-button-wrapper">
                    <div class="sale-basket-small-panel-button-icon">
                        <div class="intec-aligner"></div>
                        <i class="glyph-icon-phone"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($arResult['PERSONAL']['SHOW']) { ?>
            <?= Html::beginTag('a', [
                'class' => 'sale-basket-small-panel-button intec-grid-item',
                'href' => $arResult['URL']['PERSONAL']
            ]) ?>
            <div class="sale-basket-small-panel-button-wrapper">
                <div class="sale-basket-small-panel-button-icon">
                    <div class="intec-aligner"></div>
                    <i class="glyph-icon-lock"></i>
                </div>
            </div>
            <?= Html::endTag('a') ?>
        <?php } ?>
    </div>
</div>