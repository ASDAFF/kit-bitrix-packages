<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;
use intec\core\helpers\ArrayHelper;

?>
{{#COLUMN_LIST_SHOW}}
    <div class="basket-item-product-properties">
        {{#COLUMN_LIST}}
            {{#IS_IMAGE}}
                <?= Html::beginTag('div', [
                    'class' => 'basket-item-product-property',
                    'data' => [
                        'entity' => 'basket-item-property',
                        'product-property-type' => 'picture',
                        'mobile-hidden' => '{{#HIDE_MOBILE}}true{{/HIDE_MOBILE}}{{^HIDE_MOBILE}}false{{/HIDE_MOBILE}}'
                    ]
                ]) ?>
                    <div class="basket-item-product-property-name">
                        {{NAME}}
                    </div>
                    <div class="basket-item-product-property-values">
                        <div class="intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-4">
                            {{#VALUE}}
                                <div class="intec-grid-item-auto">
                                    <?= Html::img('{{{IMAGE_SRC}}}', [
                                        'class' => [
                                            'basket-item-product-property-value',
                                            'intec-cl-border-hover'
                                        ],
                                        'data' => [
                                            'column-property-code' => '{{CODE}}',
                                            'product-property-role' => 'image',
                                            'image-index' => '{{INDEX}}'
                                        ]
                                    ]) ?>
                                </div>
                            {{/VALUE}}
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            {{/IS_IMAGE}}
            {{#IS_TEXT}}
                <?= Html::beginTag('div', [
                    'class' => 'basket-item-product-property',
                    'data' => [
                        'entity' => 'basket-item-property',
                        'product-property-type' => 'text',
                        'mobile-hidden' => '{{#HIDE_MOBILE}}true{{/HIDE_MOBILE}}{{^HIDE_MOBILE}}false{{/HIDE_MOBILE}}'
                    ]
                ]) ?>
                    <div class="intec-grid intec-grid-wrap intec-grid-i-4">
                        <div class="intec-grid-item-auto">
                            <div class="basket-item-product-property-name">
                                {{NAME}}
                            </div>
                        </div>
                        <div class="intec-grid-item">
                            <?= Html::tag('div', '{{VALUE}}', [
                                'class' => 'basket-item-product-property-value',
                                'data' => [
                                    'entity' => 'basket-item-property-column-value',
                                    'column-property-code' => '{{CODE}}',
                                ]
                            ]) ?>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            {{/IS_TEXT}}
            {{#IS_LINK}}
                <?= Html::beginTag('div', [
                    'class' => 'basket-item-product-property',
                    'data' => [
                        'entity' => 'basket-item-property',
                        'product-property-type' => 'text',
                        'mobile-hidden' => '{{#HIDE_MOBILE}}true{{/HIDE_MOBILE}}{{^HIDE_MOBILE}}false{{/HIDE_MOBILE}}'
                    ]
                ]) ?>
                    <div class="intec-grid intec-grid-wrap intec-grid-i-4">
                        <div class="intec-grid-item-auto">
                            <div class="basket-item-product-property-name">
                                {{NAME}}
                            </div>
                        </div>
                        <div class="intec-grid-item">
                            <div class="basket-item-product-property-values">
                                {{#VALUE}}
                                    {{{LINK}}}{{^IS_LAST}},{{/IS_LAST}}
                                {{/VALUE}}
                            </div>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            {{/IS_LINK}}
        {{/COLUMN_LIST}}
    </div>
{{/COLUMN_LIST_SHOW}}