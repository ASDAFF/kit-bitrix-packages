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

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-faq',
        'c-faq-template-2'
    ],
    'data' => [
        'theme' => $arVisual['THEME']
    ]
]) ?>
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
                <div class="widget-items" data-role="items">
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    ?>
                        <div class="widget-item" id="<?= $sAreaId ?>" data-role="item" data-expanded="false">
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'widget-item-name',
                                    'intec-cl-background',
                                    'intec-cl-background-light-hover'
                                ],
                                'data' => [
                                    'role' => 'item.toggle'
                                ]
                            ]) ?>
                                <span class="widget-item-name-text">
                                    <?= $arItem['NAME'] ?>
                                </span>
                                <span class="widget-item-name-icon">
                                    <i class="fal fa-angle-down"></i>
                                </span>
                            <?= Html::endTag('div') ?>
                            <div class="widget-item-description" data-role="item.content" style="display: none">
                                <div class="widget-item-description-wrapper" style="opacity: 0">
                                    <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    <?php } else { ?>
                                        <?= $arItem['DETAIL_TEXT'] ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?= Html::endTag('div') ?>
        <?php include(__DIR__.'/parts/script.php')?>
    </div>
</div>