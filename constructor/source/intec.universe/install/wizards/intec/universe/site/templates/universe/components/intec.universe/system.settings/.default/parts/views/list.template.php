<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var CBitrixComponentTemplate $this
 */

return function ($sKey, $arProperty) use (&$arResult) { ?>
<?php
    /**
     * @var CBitrixComponentTemplate $this
     */

    $arProperty = ArrayHelper::merge([
        'view.grid' => [
            'size' => 1
        ]
    ], $arProperty);

    $bMultiple = ArrayHelper::getValue($arProperty, 'multiple');
    $bMultiple = Type::toBoolean($bMultiple);

    $arValues = ArrayHelper::getValue($arProperty, 'values');

    if (!Type::isArray($arValues))
        $arValues = [];
?>
    <div class="system-settings-property-values intec-grid intec-grid-wrap intec-grid-i-h-20 intec-grid-i-v-10" data-role="property.values">
        <?php foreach ($arValues as $arValue) { ?>
        <?php
            $sName = ArrayHelper::getValue($arValue, 'name');
            $sValue = ArrayHelper::getValue($arValue, 'value');
            $sImage = $this->GetFolder().'/images/properties/'.$sKey.'/'.$sValue.'.png';
            $bActive = false;

            if ($bMultiple) {
                if (Type::isArray($arProperty['value']))
                    $bActive = ArrayHelper::isIn($sValue, $arProperty['value']);
            } else {
                $bActive = $arProperty['value'] == $sValue;
            }
        ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'system-settings-property-value',
                    'intec-grid-item'.(!empty($arProperty['view.grid']['size']) ? '-'.$arProperty['view.grid']['size'] : null)
                ],
                'data' => [
                    'role' => 'property.value'
                ]
            ]) ?>
                <label class="system-settings-property-value-wrapper">
                    <?php if ($bMultiple) { ?>
                        <?= Html::checkbox('properties['.$sKey.'][]', $bActive, [
                            'class' => 'system-settings-property-value-input',
                            'data' => [
                                'role' => 'property.input'
                            ],
                            'value' => $sValue
                        ]) ?>
                    <?php } else { ?>
                        <?= Html::radio('properties['.$sKey.']', $bActive, [
                            'class' => 'system-settings-property-value-input',
                            'data' => [
                                'role' => 'property.input'
                            ],
                            'value' => $sValue
                        ]) ?>
                    <?php } ?>
                    <span class="system-settings-property-value-image">
                        <span class="system-settings-property-value-image-wrapper">
                            <?= Html::img($arResult['LAZYLOAD']['USE'] ? $arResult['LAZYLOAD']['STUB'] : $sImage, [
                                'alt' => $sName,
                                'title' => $sName,
                                'loading' => 'lazy',
                                'data' => [
                                    'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arResult['LAZYLOAD']['USE'] ? $sImage : null
                                ]
                            ]) ?>
                        </span>
                    </span>
                    <span class="system-settings-property-value-name">
                        <?= $sName ?>
                    </span>
                </label>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };