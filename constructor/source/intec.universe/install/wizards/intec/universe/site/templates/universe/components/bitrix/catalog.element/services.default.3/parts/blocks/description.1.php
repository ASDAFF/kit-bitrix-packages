<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-description widget">
    <div class="catalog-element-description-wrapper intec-content">
        <div class="catalog-element-description-wrapper-2 intec-content-wrapper">
            <?php if ($arBlock['HEADER']['SHOW']) { ?>
                <div class="catalog-element-description-header widget-header">
                    <?= Html::tag('div', $arBlock['HEADER']['VALUE'], [
                        'class' => [
                            'widget-title',
                            'align-'.$arBlock['HEADER']['POSITION']
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="catalog-element-description-text widget-content intec-ui-markup-text">
                <?= $arBlock['TEXT'] ?>
            </div>
        </div>
    </div>
</div>
