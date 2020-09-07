<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var IntecSaleBasketSmallComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!defined('EDITOR')) {
    if (empty($arResult['CURRENCY']))
        return;

    if (!$component->getIsBase() && !$component->getIsLite())
        return;
}

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

$arVisual = $arResult['VISUAL'];
?>

<?php if (!defined('EDITOR')) { ?>
    <div id="<?= $sTemplateId ?>" class="menu-basket">
        <!--noindex-->
        <div class="menu-basket-panel intec-content-wrap" data-role="panel">
            <div class="menu-basket-panel-wrapper">
                <?php if ($arResult['BASKET']['SHOW']) { ?>
                    <div class="menu-basket-panel-button-wrap">
                        <?= Html::beginTag('a', [
                            'class' => Html::cssClassFromArray([
                                'menu-basket-panel-button' => true,
                                'intec-grid' => true,
                                'intec-grid-a-v-center' => true,
                                'intec-grid-i-h-4' => true,
                                'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                            ], true),
                            'href' => $arResult['URL']['BASKET']
                        ]) ?>
                            <span class="menu-basket-panel-button-icon intec-grid-item-auto">
                                <i class="icon-basket glyph-icon-cart"></i>
                            </span>
                            <span class="menu-basket-panel-button-text intec-grid-item-auto">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_BASKET') ?>
                            </span>
                            <?php if ($arResult['BASKET']['COUNT'] > 0) { ?>
                                <?= Html::tag('span', $arResult['BASKET']['COUNT'], [
                                    'class' => Html::cssClassFromArray([
                                        'menu-basket-panel-button-counter' => true,
                                        'intec-grid-item-auto' => true,
                                        'intec-cl-background' => $arVisual['THEME'] == 'light' ? true : false,
                                        'intec-cl-text' => $arVisual['THEME'] == 'dark' ? true : false,
                                    ], true)
                                ]) ?>
                            <?php } ?>
                        <?= Html::endTag('a') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['DELAYED']['SHOW']) { ?>
                    <div class="menu-basket-panel-button-wrap">
                        <?= Html::beginTag('a', [
                            'class' => Html::cssClassFromArray([
                                'menu-basket-panel-button' => true,
                                'intec-grid' => true,
                                'intec-grid-a-v-center' => true,
                                'intec-grid-i-h-4' => true,
                                'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                            ], true),
                            'href' => $arResult['URL']['DELAYED']
                        ]) ?>
                            <span class="menu-basket-panel-button-icon intec-grid-item-auto">
                                <i class="icon-heart glyph-icon-heart"></i>
                            </span>
                            <span class="menu-basket-panel-button-text intec-grid-item-auto">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_DELAYED') ?>
                            </span>
                            <?php if ($arResult['DELAYED']['COUNT'] > 0) { ?>
                                <?= Html::tag('span', $arResult['DELAYED']['COUNT'], [
                                    'class' => Html::cssClassFromArray([
                                        'menu-basket-panel-button-counter' => true,
                                        'intec-grid-item-auto' => true,
                                        'intec-cl-background' => $arVisual['THEME'] == 'light' ? true : false,
                                        'intec-cl-text' => $arVisual['THEME'] == 'dark' ? true : false,
                                    ], true)
                                ]) ?>
                            <?php } ?>
                        <?= Html::endTag('a') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['COMPARE']['SHOW']) { ?>
                    <div class="menu-basket-panel-button-wrap">
                        <?= Html::beginTag('a', [
                            'class' => Html::cssClassFromArray([
                                'menu-basket-panel-button' => true,
                                'intec-grid' => true,
                                'intec-grid-a-v-center' => true,
                                'intec-grid-i-h-4' => true,
                                'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                            ], true),
                            'href' => $arResult['URL']['COMPARE']
                        ]) ?>
                            <span class="menu-basket-panel-button-icon intec-grid-item-auto">
                                <i class="icon-compare glyph-icon-compare"></i>
                            </span>
                            <span class="menu-basket-panel-button-text intec-grid-item-auto">
                                <?= Loc::getMessage('C_SALE_BASKET_SMALL_TEMPLATE_1_COMPARE') ?>
                            </span>
                            <?php if ($arResult['COMPARE']['COUNT'] > 0) { ?>
                                <?= Html::tag('span', $arResult['COMPARE']['COUNT'], [
                                    'class' => Html::cssClassFromArray([
                                        'menu-basket-panel-button-counter' => true,
                                        'intec-grid-item-auto' => true,
                                        'intec-cl-background' => $arVisual['THEME'] == 'light' ? true : false,
                                        'intec-cl-text' => $arVisual['THEME'] == 'dark' ? true : false,
                                    ], true)
                                ]) ?>
                            <?php } ?>
                        <?= Html::endTag('a') ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php include(__DIR__.'/parts/script.php') ?>
        <!--/noindex-->
    </div>
<?php } else { ?>
    <div class="constructor-element-stub">
        <div class="constructor-element-stub-wrapper">
            <?= Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_EDITOR') ?>
        </div>
    </div>
<?php } ?>
