<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 */

$arBlocks = [];

foreach ($arResult['BLOCKS'] as $sKey => &$arBlock)
    if ($arBlock['ACTIVE'])
        $arBlocks[$sKey] = $arBlock;

unset($arBlock);
unset($sKey);

$iBlocksCount = count($arBlocks);
$iBlocksCurrent = 0;

if ($iBlocksCount <= 0)
    return;

?>
<div class="news-detail-blocks">
    <?php foreach ($arBlocks as $sKey => $arBlock) { ?>
        <?= Html::beginTag('div', [
            'class' => [
                'news-detail-block'
            ],
            'data' => [
                'block' => $sKey,
                'type' => $arBlock['TYPE']
            ]
        ]) ?>
        <?php include(__DIR__.'/blocks/'.$sKey.'.php') ?>
        <?= Html::endTag('div') ?>
        <?php $iBlocksCurrent++ ?>
    <?php } ?>
</div>
