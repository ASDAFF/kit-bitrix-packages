<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

return function ($sKey, $arProperty) { ?>
<?php
    $bMultiple = ArrayHelper::getValue($arProperty, 'multiple');
    $bMultiple = Type::toBoolean($bMultiple);

    $arValues = ArrayHelper::getValue($arProperty, 'values');

    if (!Type::isArray($arValues))
        $arValues = [];

    if (empty($arValues))
        return;
?>
    <div class="system-settings-property-values" data-role="property.values">
        <?php if ($bMultiple) { ?>
            <?= Html::hiddenInput('properties['.$sKey.'][]', null) ?>
        <?php } ?>
        <?php foreach ($arValues as $arValue) { ?>
        <?php
            $sName = ArrayHelper::getValue($arValue, 'name');
            $sValue = ArrayHelper::getValue($arValue, 'value');
            $bActive = false;

            if ($bMultiple) {
                if (Type::isArray($arProperty['value']))
                    $bActive = ArrayHelper::isIn($sValue, $arProperty['value']);
            } else {
                $bActive = $arProperty['value'] == $sValue;
            }
        ?>
            <div class="system-settings-property-value" data-role="property.value">
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
                    <span class="system-settings-property-value-name">
                        <?= $sName ?>
                    </span>
                </label>
            </div>
        <?php } ?>
    </div>
<?php };