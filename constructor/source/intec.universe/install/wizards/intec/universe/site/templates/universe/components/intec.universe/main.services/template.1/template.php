<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

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
        'c-services',
        'c-services-template-1'
    ],
    'data' => [
        'columns' => $arVisual['COLUMNS']
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
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-start',
                            'a-h-'.$arVisual['ALIGNMENT'],
                            'i-5'
                        ]
                    ])
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $sDescription = $arItem['PREVIEW_TEXT'];

                        if (!empty($sDescription))
                            $sDescription = Html::stripTags($sDescription);

                        if (!empty($sDescription))
                            $sDescription = StringHelper::truncate($sDescription, 120);

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1100-3' => $arVisual['COLUMNS'] >= 4,
                                    '850-2' => $arVisual['COLUMNS'] >= 3,
                                    '600-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <div class="widget-item-fade intec-cl-background"></div>
                                <div class="widget-item-name">
                                    <?= $arItem['NAME'] ?>
                                </div>
                                <?php if ($arVisual['DESCRIPTION']['SHOW'] && !empty($sDescription)) { ?>
                                    <div class="widget-item-description">
                                        <?= $sDescription ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arVisual['DETAIL']['SHOW']) { ?>
                                    <div class="widget-item-buttons">
                                        <a class="widget-item-button" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                            <span class="widget-item-button-content">
                                                <?= $arVisual['DETAIL']['TEXT'] ?>
                                            </span>
                                            <span class="widget-item-button-icon">
                                                <i class="typcn typcn-arrow-right"></i>
                                            </span>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <a href="<?= $arBlocks['FOOTER']['BUTTON']['LINK'] ?>" class="<?= Html::cssClassFromArray([
                            'widget-footer-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'mod-transparent',
                                'mod-round-half',
                                'scheme-current',
                                'size-5'
                            ]
                        ]) ?>"><?= Html::encode($arBlocks['FOOTER']['BUTTON']['TEXT']) ?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>