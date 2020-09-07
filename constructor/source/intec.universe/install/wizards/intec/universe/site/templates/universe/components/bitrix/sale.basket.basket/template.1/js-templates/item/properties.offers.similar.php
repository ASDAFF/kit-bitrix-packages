<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
{{#HAS_SIMILAR_ITEMS}}
    <?= Html::beginTag('div', [
        'class' => [
            'basket-alert',
            'intec-ui' => [
                '',
                'control-alert',
                'scheme-orange'
            ],
        ],
        'data-entity' => 'basket-item-sku-notification'
    ]) ?>
        <div class="basket-alert-line">
            {{#USE_FILTER}}
                <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_OFFERS_SIMILAR_PART_1'), [
                    'class' => 'basket-alert-interactive',
                    'data-entity' => 'basket-item-show-similar-link'
                ]) ?>
            {{/USE_FILTER}}
            {{^USE_FILTER}}
                <?= Html::tag('span', Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P1')) ?>
            {{/USE_FILTER}}
            <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_OFFERS_SIMILAR_PART_2').' {{SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}') ?>
        </div>
        <div class="basket-alert-line">
            <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_OFFERS_SIMILAR_PART_3').' {{TOTAL_SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}?', [
                'class' => 'basket-alert-interactive',
                'data-entity' => 'basket-item-merge-sku-link'
            ]) ?>
        </div>
    <?= Html::endTag('div') ?>
{{/HAS_SIMILAR_ITEMS}}