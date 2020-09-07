<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

$oFrame = $this->createFrame();
$oFrame->begin();

?>
<div class="ns-intec-universe c-sale-basket-icons c-sale-basket-icons-default" id="<?= $sTemplateId ?>">
    <!--noindex-->
    <?= Html::beginTag('div', [
        'class' => [
            'sale-basket-icons-items',
            'intec-grid' => [
                '',
                'nowrap',
                'a-v-center',
                'i-h-10'
            ]
        ]
    ]) ?>
        <?php if ($arResult['COMPARE']['SHOW']) { ?>
            <?php $bActive = $arResult['COMPARE']['COUNT'] > 0 ?>
            <div class="sale-basket-icons-item-wrap intec-grid-item-auto">
                <?= Html::beginTag('a', [
                    'class' => Html::cssClassFromArray([
                        'sale-basket-icons-item' => [
                            '' => true,
                            'active' => $bActive
                        ],
                        'intec-cl-text' => [
                            '' => $bActive,
                            'hover' => true
                        ]
                    ], true),
                    'href' => $arResult['COMPARE']['URL']
                ]) ?>
                    <div class="sale-basket-icons-item-wrapper">
                        <i class="sale-basket-icons-item-icon glyph-icon-compare"></i>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-icons-item-counter intec-cl-background-dark">
                                <?= Html::encode($arResult['COMPARE']['COUNT']) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?= Html::endTag('a') ?>
            </div>
        <?php } ?>
        <?php if ($arResult['DELAY']['SHOW']) { ?>
            <?php $bActive = $arResult['DELAY']['COUNT'] > 0 ?>
            <div class="sale-basket-icons-item-wrap intec-grid-item-auto">
                <?= Html::beginTag('a', [
                    'class' => Html::cssClassFromArray([
                        'sale-basket-icons-item' => [
                            '' => true,
                            'active' => $bActive
                        ],
                        'intec-cl-text' => [
                            '' => $bActive,
                            'hover' => true
                        ]
                    ], true),
                    'href' => $arResult['DELAY']['URL']
                ]) ?>
                    <div class="sale-basket-icons-item-wrapper">
                        <i class="sale-basket-icons-item-icon glyph-icon-heart"></i>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-icons-item-counter intec-cl-background-dark">
                                <?= Html::encode($arResult['DELAY']['COUNT']) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?= Html::endTag('a') ?>
            </div>
        <?php } ?>
        <?php if ($arResult['BASKET']['SHOW']) { ?>
            <?php $bActive = $arResult['BASKET']['COUNT'] > 0 ?>
            <div class="sale-basket-icons-item-wrap intec-grid-item-auto">
                <?= Html::beginTag('a', [
                    'class' => Html::cssClassFromArray([
                        'sale-basket-icons-item' => [
                            '' => true,
                            'active' => $bActive
                        ],
                        'intec-cl-text' => [
                            '' => $bActive,
                            'hover' => true
                        ]
                    ], true),
                    'href' => $arResult['BASKET']['URL']
                ]) ?>
                    <div class="sale-basket-icons-item-wrapper">
                        <i class="sale-basket-icons-item-icon glyph-icon-cart"></i>
                        <?php if ($bActive) { ?>
                            <div class="sale-basket-icons-item-counter intec-cl-background-dark">
                                <?= Html::encode($arResult['BASKET']['COUNT']) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?= Html::endTag('a') ?>
            </div>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <?php if (!defined('EDITOR')) { ?>
        <script type="text/javascript">
            (function ($, api) {
                $(document).ready(function () {
                    var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                    var updated = false;
                    var update = function () {
                        if (updated)
                            return;

                        updated = true;
                        universe.components.get(<?= JavaScript::toObject([
                            'component' => $component->getName(),
                            'template' => $this->getName(),
                            'parameters' => ArrayHelper::merge($arParams, [
                                'AJAX_MODE' => 'N'
                            ])
                        ]) ?>, function (result) {
                            root.replaceWith(result);
                        });
                    };

                    universe.basket.once('update', update);
                    universe.compare.once('update', update);
                });
            })(jQuery, intec);
        </script>
    <?php } ?>
    <!--/noindex-->
</div>
<?php $oFrame->end() ?>