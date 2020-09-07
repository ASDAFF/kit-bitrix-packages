<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 * @var Closure $vItems()
 */

?>
<?= Html::beginTag('ul', [
    'class' => [
        'widget-tabs',
        'intec-ui' => [
            '',
            'control-tabs',
            'mod-block',
            'mod-position-'.$arVisual['TABS']['POSITION'],
            'scheme-current',
            'view-'.$arVisual['TABS']['VIEW']
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
            <a href="<?= '#'.$sTemplateId.'-tab-'.$iCounter ?>" role="tab" data-toggle="tab">
                <?= $arSection['NAME'] ?>
            </a>
        <?= Html::endTag('li') ?>
        <?php $iCounter++ ?>
    <?php } ?>
<?= Html::endTag('ul') ?>
<div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
    <?php $iCounter = 0 ?>
    <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
        <?= Html::beginTag('div', [
            'id' => $sTemplateId.'-tab-'.$iCounter,
            'class' => Html::cssClassFromArray([
                'intec-ui-part-tab' => true,
                'fade' => true,
                'in active' => $iCounter === 0
            ], true),
            'role' => 'tabpanel'
        ]) ?>
            <?php $vItems($arSection['ITEMS']) ?>
        <?= Html::endTag('div') ?>
        <?php $iCounter++ ?>
    <?php } ?>
</div>