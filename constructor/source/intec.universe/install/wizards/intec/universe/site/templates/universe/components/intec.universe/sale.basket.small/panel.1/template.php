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
    <?php $oFrame = $this->createFrame()->begin() ?>
        <div id="<?= $sTemplateId ?>" class="ns-intec-universe c-sale-basket-small c-sale-basket-small-panel-1">
            <!--noindex-->
            <div class="sale-basket-small-panel intec-content-wrap" data-role="panel">
                <div class="sale-basket-small-panel-wrapper intec-grid intec-grid-nowrap">
                    <?php if ($arResult['BASKET']['SHOW']) { ?>
                        <?= Html::beginTag('a', [
                            'class' => 'sale-basket-small-panel-button intec-grid-item',
                            'href' => $arResult['URL']['BASKET']
                        ]) ?>
                        <div class="sale-basket-small-panel-button-wrapper">
                            <div class="sale-basket-small-panel-button-icon-wrap">
                                <div class="intec-aligner"></div>
                                <div class="sale-basket-small-panel-button-icon">
                                    <svg width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.2631 13.0339H8.90819L5.8923 4.60868H22.7309L20.0387 11.1724C19.5768 12.2985 18.4803 13.0339 17.2631 13.0339Z" fill="#fff" class="<?= $arResult['BASKET']['COUNT'] > 0 ? "active" : null ?>"/>
                                        <path d="M1.67419 1.44922H4.05716C4.47951 1.44922 4.85632 1.71456 4.99866 2.1122L5.8923 4.60868M8.90819 13.0339H17.2631C18.4803 13.0339 19.5768 12.2985 20.0387 11.1724L22.7309 4.60868H5.8923M8.90819 13.0339L5.8923 4.60868M8.90819 13.0339C8.90819 13.0339 5.7285 12.953 5.7285 15.3048C5.7285 17.6566 8.90819 17.4111 8.90819 17.4111H20.6904" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="8.41481" cy="22.6223" r="2.1921" stroke="#333333" stroke-width="2"/>
                                        <circle cx="17.9156" cy="22.6223" r="2.1921" stroke="#333333" stroke-width="2"/>
                                    </svg>
                                </div>
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
                            <div class="sale-basket-small-panel-button-icon-wrap">
                                <div class="intec-aligner"></div>
                                <div class="sale-basket-small-panel-button-icon">
                                    <svg width="27" height="23" viewBox="0 0 27 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.5383 4.31155L13.0179 4.7911C13.4382 5.21146 14.1154 5.22528 14.5526 4.82241L15.101 4.31699C16.4192 3.10218 17.8871 1.86094 19.6797 1.86556C21.0349 1.86905 22.6558 2.34115 24.1683 3.85371C26.9239 6.60923 25.3165 9.85432 24.1683 11.1324L14.5538 20.7469C14.121 21.1797 13.4193 21.1797 12.9865 20.7469L3.37203 11.1324C2.11558 9.96263 0.356561 6.86918 3.37203 3.85371C5.09741 2.12833 6.86251 1.66819 8.28019 1.74041C9.99232 1.82764 11.3261 3.09933 12.5383 4.31155Z" stroke="#333333" stroke-width="2" class="<?= $arResult['DELAYED']['COUNT'] > 0 ? "active" : null ?>"/>
                                    </svg>
                                </div>
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
                            <div class="sale-basket-small-panel-button-icon-wrap">
                                <div class="intec-aligner"></div>
                                <div class="sale-basket-small-panel-button-icon">
                                    <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <line x1="2.1897" y1="19.2673" x2="2.1897" y2="12.132" stroke="#333333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="11.6655" y1="19.2673" x2="11.6655" y2="1.99661" stroke="#333333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        <line x1="21.1414" y1="19.2673" x2="21.1414" y2="5.26465" stroke="#333333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
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
                                <div class="sale-basket-small-panel-button-icon-wrap">
                                    <div class="intec-aligner"></div>
                                    <div class="sale-basket-small-panel-button-icon">
                                        <svg width="26" height="23" viewBox="0 0 26 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M23.7095 1.48059H2.52408C2.28997 1.48059 2.06544 1.57359 1.8999 1.73914C1.73436 1.90468 1.64136 2.1292 1.64136 2.36332V16.4869C1.64136 16.721 1.73436 16.9456 1.8999 17.1111C2.06544 17.2766 2.28997 17.3696 2.52408 17.3696H9.30341L15.253 21.6156C15.402 21.7234 15.581 21.7821 15.765 21.7833C15.9059 21.7816 16.0446 21.7484 16.171 21.6862C16.3149 21.6116 16.4356 21.4989 16.5196 21.3603C16.6037 21.2217 16.648 21.0626 16.6477 20.9005V17.3696H23.7095C23.9436 17.3696 24.1681 17.2766 24.3337 17.1111C24.4992 16.9456 24.5922 16.721 24.5922 16.4869V2.36332C24.5922 2.1292 24.4992 1.90468 24.3337 1.73914C24.1681 1.57359 23.9436 1.48059 23.7095 1.48059ZM6.93771 11.1906C6.58854 11.1906 6.2472 11.087 5.95688 10.893C5.66655 10.699 5.44027 10.4233 5.30665 10.1007C5.17302 9.77813 5.13806 9.42316 5.20618 9.0807C5.2743 8.73823 5.44244 8.42366 5.68935 8.17676C5.93625 7.92985 6.25082 7.76171 6.59329 7.69359C6.93575 7.62547 7.29072 7.66043 7.61332 7.79405C7.93591 7.92768 8.21164 8.15396 8.40563 8.44429C8.59962 8.73461 8.70316 9.07595 8.70316 9.42512C8.70316 9.89335 8.51716 10.3424 8.18607 10.6735C7.85499 11.0046 7.40594 11.1906 6.93771 11.1906ZM13.1168 11.1906C12.7676 11.1906 12.4263 11.087 12.136 10.893C11.8456 10.699 11.6193 10.4233 11.4857 10.1007C11.3521 9.77813 11.3171 9.42316 11.3853 9.0807C11.4534 8.73823 11.6215 8.42366 11.8684 8.17676C12.1153 7.92985 12.4299 7.76171 12.7724 7.69359C13.1148 7.62547 13.4698 7.66043 13.7924 7.79405C14.115 7.92768 14.3907 8.15396 14.5847 8.44429C14.7787 8.73461 14.8822 9.07595 14.8822 9.42512C14.8822 9.89335 14.6962 10.3424 14.3651 10.6735C14.0341 11.0046 13.585 11.1906 13.1168 11.1906ZM19.2959 11.1906C18.9467 11.1906 18.6054 11.087 18.315 10.893C18.0247 10.699 17.7984 10.4233 17.6648 10.1007C17.5312 9.77813 17.4962 9.42316 17.5643 9.0807C17.6325 8.73823 17.8006 8.42366 18.0475 8.17676C18.2944 7.92985 18.609 7.76171 18.9514 7.69359C19.2939 7.62547 19.6489 7.66043 19.9715 7.79405C20.2941 7.92768 20.5698 8.15396 20.7638 8.44429C20.9578 8.73461 21.0613 9.07595 21.0613 9.42512C21.0613 9.89335 20.8753 10.3424 20.5442 10.6735C20.2131 11.0046 19.7641 11.1906 19.2959 11.1906Z" stroke="#333333" stroke-width="2"/>
                                        </svg>
                                    </div>
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
                            <div class="sale-basket-small-panel-button-icon-wrap">
                                <div class="intec-aligner"></div>
                                <div class="sale-basket-small-panel-button-icon">
                                    <svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.6138 21.7691V19.5164C19.6138 18.3215 19.1392 17.1755 18.2942 16.3306C17.4493 15.4857 16.3033 15.011 15.1084 15.011H6.09758C4.90267 15.011 3.7567 15.4857 2.91177 16.3306C2.06684 17.1755 1.59216 18.3215 1.59216 19.5164V21.7691" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M10.6031 10.5056C13.0914 10.5056 15.1085 8.48845 15.1085 6.00017C15.1085 3.5119 13.0914 1.49475 10.6031 1.49475C8.1148 1.49475 6.09766 3.5119 6.09766 6.00017C6.09766 8.48845 8.1148 10.5056 10.6031 10.5056Z" stroke="#333333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <?= Html::endTag('a') ?>
                    <?php } ?>
                </div>
            </div>
            <?php include(__DIR__.'/parts/script.php') ?>
            <!--/noindex-->
        </div>
    <?php $oFrame->beginStub() ?>
    <?php $oFrame->end() ?>
<?php } else { ?>
    <div class="constructor-element-stub">
        <div class="constructor-element-stub-wrapper">
            <?= Loc::getMessage('C_SALE_BASKET_SMALL_PANEL_1_EDITOR') ?>
        </div>
    </div>
<?php } ?>
