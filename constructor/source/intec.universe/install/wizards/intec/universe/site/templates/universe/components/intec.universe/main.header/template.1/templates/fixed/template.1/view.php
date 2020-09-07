<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sTemplateType = $arData['type'];
$sSelector = $arData['selector'];

$bBasketShow =
    $arResult['BASKET']['SHOW']['FIXED'] ||
    $arResult['DELAY']['SHOW']['FIXED'] ||
    $arResult['COMPARE']['SHOW']['FIXED'];

?>
<div class="widget-view-fixed-1">
    <div class="widget-wrapper intec-content intec-content-visible intec-content-primary">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="widget-wrapper-3 intec-grid intec-grid-nowrap intec-grid-i-h-20 intec-grid-a-v-center">
                <?php if ($arResult['FIXED']['MENU']['POPUP']['SHOW']) { ?>
                    <div class="widget-menu-popup-wrap intec-grid-item-auto">
                        <div class="widget-menu-popup">
                            <?php include(__DIR__.'/../../../parts/menu/main.popup.php') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arResult['LOGOTYPE']['SHOW']['FIXED']) { ?>
                    <div class="widget-logotype-wrap intec-grid-item-auto">
                        <?= Html::beginTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div', [
                            'href' => $arResult['LOGOTYPE']['LINK']['USE'] ? $arResult['LOGOTYPE']['LINK']['VALUE'] : null,
                            'class' => [
                                'widget-item',
                                'widget-logotype',
                                'intec-image'
                            ]
                        ]) ?>
                            <div class="intec-aligner"></div>
                            <?php include(__DIR__.'/../../../parts/logotype.php') ?>
                        <?= Html::endTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div') ?>
                    </div>
                <?php } ?>
                <?php if ($arResult['MENU']['MAIN']['SHOW']['FIXED']) { ?>
                    <div class="widget-menu-wrap intec-grid-item intec-grid-item-a-stretch">
                        <div class="widget-item widget-menu">
                            <?php $arMenuParams = ['TRANSPARENT' => 'Y'] ?>
                            <?php include(__DIR__.'/../../../parts/menu/main.horizontal.1.php') ?>
                        </div>
                        <script>
                            (function ($, api) {
                                $(document).on('ready', function () {
                                    var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                                    var view = $(<?= JavaScript::toObject($sSelector) ?>, root);
                                    var menu = $('.widget-menu .ns-bitrix.c-menu.c-menu-horizontal-1', view);

                                    view.on('show', function () {
                                        menu.trigger('update');
                                    });
                                });
                            })(jQuery, intec);
                        </script>
                    </div>
                <?php } else { ?>
                    <div class="intec-grid-item"></div>
                <?php } ?>
                <?php if ($bBasketShow) { ?>
                    <div class="widget-basket-wrap intec-grid-item-auto">
                        <div class="widget-item widget-basket">
                            <?php include(__DIR__.'/../../../parts/basket.php') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arResult['SEARCH']['SHOW']['FIXED']) { ?>
                    <div class="widget-search-wrap intec-grid-item-auto">
                        <div class="widget-item widget-search">
                            <?php $arSearchParams = [
                                'INPUT_ID' => $arParams['SEARCH_INPUT_ID'].'-fixed'
                            ] ?>
                            <?php include(__DIR__.'/../../../parts/search/popup.1.php') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arResult['AUTHORIZATION']['SHOW']['FIXED']) { ?>
                    <div class="widget-authorization-wrap intec-grid-item-auto">
                        <div class="widget-item widget-authorization">
                            <?php include(__DIR__.'/../../../parts/auth/icons.php') ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
