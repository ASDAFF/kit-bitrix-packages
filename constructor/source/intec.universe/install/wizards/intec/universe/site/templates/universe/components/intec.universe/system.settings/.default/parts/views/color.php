<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

return function ($sKey, $arProperty) { ?>
<?php
    $arValues = ArrayHelper::getValue($arProperty, 'values');
    $bCustom = true;

    if (!Type::isArray($arValues))
        $arValues = [];
?>
    <?= Html::hiddenInput('properties['.$sKey.']', $arProperty['value'], [
        'data' => [
            'role' => 'property.input'
        ]
    ]) ?>
    <div class="system-settings-property-values" data-role="property.values">
        <?php foreach ($arValues as $sValue) { ?>
        <?php
            $bActive = $arProperty['value'] === $sValue;

            if ($bActive)
                $bCustom = false;
        ?>
            <?= Html::beginTag('div', [
                'class' => 'system-settings-property-value',
                'data' => [
                    'role' => 'property.value',
                    'active' => $bActive ? 'true' : 'false',
                    'value' => $sValue
                ]
            ]) ?>
                <div class="system-settings-property-value-color" style="background: <?= $sValue ?>">
                    <div class="intec-aligner"></div>
                    <i class="system-settings-property-value-icon far fa-check"></i>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
        <?= Html::beginTag('div', [
            'class' => 'system-settings-property-value',
            'data' => [
                'role' => 'property.value',
                'active' => $bCustom ? 'true' : 'false',
                'value' => 'custom'
            ]
        ]) ?>
            <?= Html::beginTag('div', [
                'class' => 'system-settings-property-value-color',
                'data' => [
                    'role' => 'property.value.background'
                ],
                'style' => [
                    'background' => !empty($arProperty['value']) ? $arProperty['value'] : null
                ]
            ]) ?>
                <div class="intec-aligner"></div>
                <i class="system-settings-property-value-icon far fa-eye-dropper"></i>
            <?= Html::endTag('div') ?>
        <?= Html::endTag('div') ?>
    </div>
<?php };