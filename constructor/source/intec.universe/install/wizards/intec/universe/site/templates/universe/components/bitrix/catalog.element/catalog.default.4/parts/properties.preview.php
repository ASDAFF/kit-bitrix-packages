<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var array $arData
 */

?>
<?= Html::beginTag('div', [
    'class' => Html::cssClassFromArray([
        'catalog-element-properties-preview' => true,
        'catalog-element-block' => $arVisual['PROPERTIES']['PREVIEW']['POSITION'] === 'right'
    ], true)
]) ?>
    <div class="intec-grid intec-grid-wrap intec-grid-i-10">
        <?php foreach ($arData['PROPERTIES'] as $arProperty) { ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'intec-grid-item' => [
                        $arVisual['PROPERTIES']['PREVIEW']['COLUMNS'] => true,
                        '1200-2' => $arVisual['PROPERTIES']['PREVIEW']['COLUMNS'] > 2
                    ]
                ], true)
            ]) ?>
                <div class="catalog-element-properties-preview-wrapper">
                    <?= Html::tag('div', $arProperty['NAME'], [
                        'class' => 'catalog-element-properties-preview-name'
                    ]) ?>
                    <?= Html::tag('div', $arProperty['VALUE'], [
                        'class' => 'catalog-element-properties-preview-value'
                    ]) ?>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
        <?php unset($arProperty) ?>
    </div>
<?= Html::endTag('div') ?>