<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>
<div class="basket-item-price-along">
    <div class="basket-item-price-along-value">
        {{#SHOW_PRICE_FOR}}
            <?= Html::tag('span', '{{{PRICE_FORMATED}}}', [
                'id' => 'basket-item-price-{{ID}}'
            ]) ?>
            <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_PRICE_ALONG_DELIMITER')) ?>
            <?= Html::tag('span', '{{MEASURE_RATIO}}') ?>
            <?= Html::tag('span', '{{MEASURE_TEXT}}') ?>
        {{/SHOW_PRICE_FOR}}
        {{^SHOW_PRICE_FOR}}
            <?= Html::tag('span', '{{MEASURE_TEXT}}') ?>
        {{/SHOW_PRICE_FOR}}
    </div>
    {{#SHOW_DISCOUNT_PRICE}}
        <div class="basket-item-price-along-discount">
            {{{FULL_PRICE_FORMATED}}}
        </div>
    {{/SHOW_DISCOUNT_PRICE}}
</div>