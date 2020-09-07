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
$iSideCounter = 0;

?>
<div class="widget c-stages c-stages-template-1" id="<?= $sTemplateId ?>">
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
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sName = $arItem['NAME'];
                    $sDescription = $arItem['PREVIEW_TEXT'];
                    $iCount++;
                    $iSideCounter++;
                    $bLeft = $iSideCounter % 2 == 1;

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-item' => true,
                            'widget-item-left' => $bLeft,
                            'widget-item-right' => !$bLeft
                        ], true)
                    ]) ?>
                        <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                            <?php if ($arVisual['COUNT']['SHOW']) { ?>
                                <div class="widget-item-number">
                                    <?= $iCount.'.' ?>
                                </div>
                            <?php } ?>
                            <div class="widget-item-name">
                                <?= $sName ?>
                            </div>
                            <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($sDescription)) { ?>
                                <div class="widget-item-description">
                                    <?= $sDescription ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
