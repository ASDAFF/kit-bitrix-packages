<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var Closure $vItems()
 */

?>
<div class="widget-tabs">
    <?php if ($arVisual['WIDE']) { ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('ul', [
        'class' => [
            'intec-ui' => [
                '',
                'control-tabs',
                'mod-block',
                'mod-position-'.$arVisual['TABS']['POSITION'],
                'scheme-current',
                'view-1'
            ]
        ]
    ]) ?>
        <?php $iCounter = 0 ?>
        <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
            <?= Html::beginTag('li', [
                'class' => Html::cssClassFromArray([
                    'intec-ui-part-tab' => true,
                    'active' => $iCounter === 0
                ], true)
            ]) ?>
                <?= Html::tag('a', $arSection['NAME'], [
                    'href' => '#'.$sTemplateId.'-tab-'.$iCounter,
                    'role' => 'tab',
                    'data' => [
                        'toggle' => 'tab'
                    ]
                ]) ?>
            <?= Html::endTag('li') ?>
            <?php $iCounter++ ?>
        <?php } ?>
    <?= Html::endTag('ul') ?>
    <?php if ($arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
<div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
    <?php $iCounter = 0 ?>
    <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
        <?= Html::beginTag('div', [
            'id' => $sTemplateId.'-tab-'.$iCounter,
            'class' => Html::cssClassFromArray([
                'widget-tabs-content-item' => true,
                'intec-ui-part-tab' => true,
                'active' => $iCounter === 0
            ], true),
            'role' => 'tabpanel'
        ]) ?>
            <?php $vItems($arSection['ITEMS']) ?>
        <?= Html::endTag('div') ?>
        <?php $iCounter++ ?>
    <?php } ?>
</div>
