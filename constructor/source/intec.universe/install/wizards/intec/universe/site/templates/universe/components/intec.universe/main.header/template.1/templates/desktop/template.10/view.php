<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arParams
 * @var array $arData
 * @var CAllMain $APPLICATION
 */

$sTemplateId = $arData['id'];

$arVisual = $arResult['VISUAL'];

?>
<div class="widget-view-desktop-10">
    <div class="intec-content intec-content-primary intec-content-visible">
        <div class="intec-content-wrapper">
            <div class="widget-container">
                <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-16">
                    <?php if ($arResult['LOGOTYPE']['SHOW']['DESKTOP']) { ?>
                        <div class="widget-logotype-container intec-grid-item-auto">
                            <?= Html::beginTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div', [
                                'href' => $arResult['LOGOTYPE']['LINK']['USE'] ? $arResult['LOGOTYPE']['LINK']['VALUE'] : null,
                                'class' => 'widget-logotype'
                            ]) ?>
                                <?php include(__DIR__.'/../../../parts/logotype.php') ?>
                            <?= Html::endTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div') ?>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['TAGLINE']['SHOW']['DESKTOP']) { ?>
                        <div class="widget-tag-line-container intec-grid-item-auto">
                            <div class="widget-tag-line">
                                <?= $arResult['TAGLINE']['VALUE'] ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['REGIONALITY']['USE']) { ?>
                        <div class="widget-region-container intec-grid-item-auto">
                            <div class="widget-region">
                                <?php $APPLICATION->IncludeComponent(
                                    'intec.regionality:regions.select',
                                    '', []
                                ) ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="intec-grid-item"></div>
                    <?php if ($arResult['CONTACTS']['SHOW']['DESKTOP'] || $arResult['FORMS']['CALL']['SHOW']) { ?>
                        <div class="widget-information-container intec-grid-item-auto">
                            <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-8">
                                <?php if ($arResult['CONTACTS']['SHOW']['DESKTOP']) {
                                    include(__DIR__.'/parts/contacts.php');
                                } ?>
                                <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                                    <div class="widget-call-container intec-grid-item-auto">
                                        <?= Html::tag('div', Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP10_BUTTON'), [
                                            'class' => [
                                                'widget-call',
                                                'intec-cl-text'
                                            ],
                                            'data-action' => 'forms.call.open'
                                        ]) ?>
                                        <?php include(__DIR__.'/../../../parts/forms/call.php') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['SEARCH']['SHOW']['DESKTOP']) { ?>
                        <div class="widget-search-container intec-grid-item-auto">
                            <div class="widget-search">
                                <?php $arSearchParams = [
                                    'INPUT_ID' => $arParams['SEARCH_INPUT_ID'].'-desktop'
                                ] ?>
                                <?php include(__DIR__.'/../../../parts/search/popup.1.php') ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['AUTHORIZATION']['SHOW']['DESKTOP']) { ?>
                        <div class="widget-authorization-container intec-grid-item-auto">
                            <div class="widget-authorization">
                                <?php include(__DIR__.'/../../../parts/auth/panel.2.php') ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['BASKET']['SHOW']['DESKTOP'] || $arResult['DELAY']['SHOW']['DESKTOP'] || $arResult['COMPARE']['SHOW']['DESKTOP']) { ?>
                        <div class="widget-basket-container intec-grid-item-auto">
                            <div class="widget-basket">
                                <?php include(__DIR__.'/../../../parts/basket.php') ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($arVisual['TRANSPARENCY']) { ?>
        <div class="intec-content intec-content-primary intec-content-visible">
            <div class="intec-content-wrapper">
    <?php } ?>
        <div class="widget-menu">
            <?php $arMenuParams = ['TRANSPARENT' => 'N'] ?>
            <?php include(__DIR__.'/../../../parts/menu/main.horizontal.1.php') ?>
        </div>
    <?php if ($arVisual['TRANSPARENCY']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
