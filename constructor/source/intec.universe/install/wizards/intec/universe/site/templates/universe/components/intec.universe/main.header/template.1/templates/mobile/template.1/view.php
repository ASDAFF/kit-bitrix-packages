<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\Html;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sTemplateType = $arData['type'];

?>
<?= Html::beginTag('div', [
    'class' => Html::cssClassFromArray([
        'widget-view-mobile-1' => [
            '' => true,
            'filled' => $arResult['MOBILE']['FILLED']
        ],
        'intec-cl-background' => $arResult['MOBILE']['FILLED'],
        'intec-content-wrap' => true
    ], true)
]) ?>
    <div class="widget-wrapper intec-content intec-content-visible intec-content-primary">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="widget-wrapper-3 intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                <?php if ($arResult['MENU']['MAIN']['SHOW']['MOBILE']) { ?>
                    <div class="widget-menu-wrap intec-grid-item-auto">
                        <div class="widget-item widget-menu">
                            <?php include(__DIR__.'/../../../parts/menu/main.mobile.1.php') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arResult['LOGOTYPE']['SHOW']['MOBILE']) { ?>
                    <div class="widget-logotype-wrap intec-grid-item intec-grid-item-shrink-1">
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
                <?php } else { ?>
                    <div class="intec-grid-item intec-grid-item-shrink-1"></div>
                <?php } ?>
                <?php if ($arResult['SEARCH']['SHOW']['MOBILE']) { ?>
                    <?php if ($arResult['MOBILE']['SEARCH']['TYPE'] === 'page') { ?>
                        <?php
                            $oSearchUrl = new Url($arResult['SEARCH']['MODE'] === 'site' ? $arResult['URL']['SEARCH'] : $arResult['URL']['CATALOG']);
                            $oSearchUrl->getQuery()->set('q', '');
                        ?>
                        <div class="widget-search-wrap intec-grid-item-auto">
                            <a href="<?= $oSearchUrl->build() ?>" class="widget-item widget-search intec-cl-text-hover">
                                <i class="glyph-icon-loop"></i>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="widget-search-wrap intec-grid-item-auto">
                            <div class="widget-item widget-search">
                                <?php $arSearchParams = [
                                    'INPUT_ID' => $arParams['SEARCH_INPUT_ID'].'-mobile'
                                ] ?>
                                <?php include(__DIR__.'/../../../parts/search/popup.1.php') ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if ($arResult['BASKET']['SHOW']['MOBILE'] || $arResult['COMPARE']['SHOW']['MOBILE']) { ?>
                    <div class="widget-basket-wrap intec-grid-item-auto">
                        <div class="widget-item widget-basket">
                            <?php include(__DIR__.'/../../../parts/basket.php') ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($arResult['AUTHORIZATION']['SHOW']['MOBILE']) { ?>
                    <div class="widget-authorization-wrap intec-grid-item-auto">
                        <div class="widget-item widget-authorization">
                            <?php include(__DIR__.'/../../../parts/auth/icons.php') ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
