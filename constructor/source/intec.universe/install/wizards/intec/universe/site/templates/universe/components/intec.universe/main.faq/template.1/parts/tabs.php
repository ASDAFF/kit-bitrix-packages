<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arBlocks
 * @var array $arVisual
 */

if (empty($arResult['SECTIONS']))
    return;

?>
<ul class="<?= Html::cssClassFromArray([
    'widget-tabs',
    'intec-ui' => [
        '',
        'control-tabs',
        'mod-block',
        'mod-position-'.$arVisual['TABS']['POSITION'],
        'scheme-current',
        'view-1'
    ]
]) ?>">
    <?php $iCounter = 0 ?>
    <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
        <li class="<?= Html::cssClassFromArray([
            'intec-ui-part-tab' => true,
            'active' => $iCounter === 0
        ], true) ?>">
            <a href="<?= '#'.$sTemplateId.'-tab-'.$iCounter ?>" role="tab" data-toggle="tab"><?= $arSection['NAME'] ?></a>
        </li>
        <?php $iCounter++ ?>
    <?php } ?>
</ul>
<div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
    <?php $iCounter = 0 ?>
    <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
        <?= Html::beginTag('div', [
            'id' => $sTemplateId.'-tab-'.$iCounter,
            'class' => Html::cssClassFromArray([
                'intec-ui-part-tab' => true,
                'active' => $iCounter === 0
            ], true),
            'role' => 'tabpanel'
        ]) ?>
            <?php $arItems = &$arSection['ITEMS'] ?>
            <?php include(__DIR__.'/items.php') ?>
        <?= Html::endTag('div') ?>
        <?php $iCounter++ ?>
    <?php } ?>
</div>