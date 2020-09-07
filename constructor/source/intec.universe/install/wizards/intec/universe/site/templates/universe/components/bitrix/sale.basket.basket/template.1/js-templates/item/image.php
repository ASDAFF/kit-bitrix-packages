<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<div class="basket-item-image-wrap intec-grid-item-auto intec-grid-item-800-1">
    <div class="basket-item-image">
        {{#DETAIL_PAGE_URL}}
            <?= Html::beginTag('a', [
                'class' => 'basket-item-image-link',
                'href' => '{{DETAIL_PAGE_URL}}'
            ]) ?>
        {{/DETAIL_PAGE_URL}}
            <?= Html::img('{{{IMAGE_URL}}}{{^IMAGE_URL}}'.SITE_TEMPLATE_PATH.'/images/picture.missing.png{{/IMAGE_URL}}', [
                'class' => 'intec-image-effect'
            ]) ?>
            {{#SHOW_LABEL}}
                <?php $arPosition = explode('-', $arParams['LABEL_PROP_POSITION']) ?>
                <?= Html::beginTag('div', [
                    'class' => 'basket-item-image-stickers',
                    'data' => [
                        'position-y' => $arPosition[0],
                        'position-x' => $arPosition[1],
                        'print' => 'false'
                    ]
                ]) ?>
                    <div class="basket-item-image-stickers-wrapper">
                        {{#LABEL_VALUES}}
                            <div class="basket-item-image-sticker">
                                <?= Html::tag('span', '{{NAME}}', [
                                    'class' => 'basket-item-image-sticker-value',
                                    'data-mobile-hidden' => '{{#HIDE_MOBILE}}true{{/HIDE_MOBILE}}{{^HIDE_MOBILE}}false{{/HIDE_MOBILE}}'
                                ]) ?>
                            </div>
                        {{/LABEL_VALUES}}
                    </div>
                <?= Html::endTag('div') ?>
                <?php unset($arPosition) ?>
            {{/SHOW_LABEL}}
            <?php if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') { ?>
                <?php $arPosition = explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) ?>
                {{#DISCOUNT_PRICE_PERCENT}}
                    <?= Html::beginTag('div', [
                        'class' => 'basket-item-image-stickers',
                        'data' => [
                            'position-y' => $arPosition[0],
                            'position-x' => $arPosition[1],
                            'print' => 'false'
                        ]
                    ]) ?>
                        <div class="basket-item-image-stickers-wrapper">
                            <div class="basket-item-image-sticker">
                                <div class="basket-item-image-sticker-value">
                                    -{{DISCOUNT_PRICE_PERCENT_FORMATED}}
                                </div>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                {{/DISCOUNT_PRICE_PERCENT}}
                <?php unset($arPosition) ?>
            <?php } ?>
        {{#DETAIL_PAGE_URL}}
            <?= Html::endTag('a') ?>
        {{/DETAIL_PAGE_URL}}
    </div>
</div>