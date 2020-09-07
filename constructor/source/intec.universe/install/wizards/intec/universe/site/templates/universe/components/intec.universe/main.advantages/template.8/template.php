<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$iCount = 0;

?>
<div class="widget c-advantages c-advantages-template-8" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                        <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                            <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-start',
                            'a-h-'.$arVisual['ALIGNMENT'],
                            'i-15'
                        ]
                    ]
                ]) ?>
                    <?php foreach ($arResult ['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $iCount++;

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1100-4' => $arVisual['COLUMNS'] >= 5,
                                    '850-2' => $arVisual['COLUMNS'] >= 3,
                                    '500-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <div class="widget-item-number">
                                    <?= $iCount ?>
                                </div>
                                <div class="widget-item-name">
                                    <?= $arItem['NAME'] ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <? } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>