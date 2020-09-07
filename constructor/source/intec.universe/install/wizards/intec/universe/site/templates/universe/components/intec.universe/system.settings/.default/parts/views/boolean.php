<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;use intec\core\helpers\Html;

return function ($sKey, $arProperty) { ?>
    <div class="system-settings-property-value">
        <div class="system-settings-value-parts intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
            <div class="system-settings-value-part intec-grid-item-auto">
                <?= Html::hiddenInput('properties['.$sKey.']', 0) ?>
                <?= Html::checkbox('properties['.$sKey.']', $arProperty['value'], [
                    'data' => [
                        'role' => 'property.input'
                    ]
                ]) ?>
            </div>
            <?php if ($arProperty['title'] === 'inner') { ?>
                <?php $sName = ArrayHelper::getValue($arProperty, 'name') ?>
                <div class="system-settings-value-part intec-grid-item intec-grid-item-shrink-1">
                    <div class="system-settings-property-value-name">
                        <?= $sName ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php };