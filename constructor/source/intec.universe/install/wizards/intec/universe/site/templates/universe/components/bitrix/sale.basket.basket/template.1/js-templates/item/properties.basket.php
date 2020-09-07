<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 */

if (!ArrayHelper::isIn('PROPS', $arParams['COLUMNS_LIST']))
    return;

?>
{{#PROPS_SHOW}}
    <?= Html::beginTag('div', [
        'class' => 'basket-item-basket-properties',
        'data-mobile-hidden' => ArrayHelper::keyExists('PROPS', $mobileColumns) ? 'false' : 'true'
    ]) ?>
        {{#PROPS}}
            <div class="basket-item-basket-property">
                <div class="intec-grid intec-grid-wrap intec-grid-i-4">
                    <div class="intec-grid-item-auto">
                        <div class="basket-item-basket-property-name">
                            {{{NAME}}}
                        </div>
                    </div>
                    <div class="intec-grid-item">
                        <?= Html::tag('div', '{{{VALUE}}}', [
                            'class' => 'basket-item-basket-property-value',
                            'data' => [
                                'entity' => 'basket-item-property-value',
                                'property-code' => '{{CODE}}'
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        {{/PROPS}}
    <?= Html::endTag('div') ?>
{{/PROPS_SHOW}}