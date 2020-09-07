<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
<div class="widget c-stages c-stages-template-2" id="<?= $sTemplateId ?>">
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
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-content',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-v-stretch',
                        'a-h-center',
                        'i-h-20'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult ['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sName = $arItem['NAME'];
                    $sDescription = $arItem['PREVIEW_TEXT'];
                    $iCount++;

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-item' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '1150-3' => $arVisual['COLUMNS'] >= 4,
                                '850-2' => $arVisual['COLUMNS'] >= 3,
                                '600-1' => $arVisual['COLUMNS'] >= 2
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                            <div class="widget-item-number">
                                <?= $iCount ?>
                            </div>
                            <div class="widget-item-text">
                                <?= $sName ?>
                                <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($sDescription)) { ?>
                                    <?= ' - '.$sDescription ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <? } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>


