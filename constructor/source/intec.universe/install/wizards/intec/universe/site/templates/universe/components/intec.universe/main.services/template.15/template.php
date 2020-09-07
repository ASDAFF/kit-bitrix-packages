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

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';
$iCounter = 0

?>
<div class="widget c-services c-services-template-15">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                        <div class="widget-title">
                            <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <div class="widget-description">
                            <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('ul', [
                    'class' => [
                        'widget-tabs',
                        'intec-ui' => [
                            '',
                            'control-tabs',
                            'mod-block',
                            'mod-position-'.$arVisual['TABS']['POSITION'],
                            'scheme-current',
                            'view-3'
                        ],
                        'intec-grid',
                        'intec-grid-a-v-start',
                        'intec-grid-i-h-17',
                        'intec-grid-i-v-25',
                        'intec-grid-wrap'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 160,
                                'height' => 160
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        ?>
                        <?= Html::beginTag('li', [
                            'class' => Html::cssClassFromArray([
                                'intec-ui-part-tab' => true,
                                'intec-grid-item' => [
                                    '6' => true,
                                    '1024-4' => true,
                                    '768-3' => true,
                                    '500-2' => true,
                                    '400-1' => true
                                ],
                                'active' => $iCounter === 0
                            ], true)
                        ]) ?>
                        <a href="<?= '#'.$sTemplateId.'-tab-'.$iCounter ?>" role="tab" data-toggle="tab">
                            <div class="intec-ui-part-tab-picture">
                                <img loading="lazy" src="<?= $sPicture ?>" alt="<?= $arItem['NAME'] ?>">
                            </div>
                            <div class="intec-ui-part-tab-name">
                                <?= $arItem['NAME'] ?>
                            </div>
                        </a>
                        <?= Html::endTag('li') ?>
                        <?php $iCounter++ ?>
                    <?php } ?>
                <?= Html::endTag('ul') ?>
                <div class="widget-items">
                    <div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
                        <?php $iCounter = 0 ?>
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $arData = $arItem['DATA'];

                            ?>
                            <?= Html::beginTag('div', [
                                'id' => $sTemplateId.'-tab-'.$iCounter,
                                'class' => Html::cssClassFromArray([
                                    'intec-ui-part-tab' => true,
                                    'fade' => true,
                                    'in active' => $iCounter === 0
                                ], true),
                                'role' => 'tabpanel'
                            ]) ?>
                                <div class="widget-item" id="<?= $sAreaId ?>">
                                    <div class="widget-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                </div>
                            <?= Html::endTag('div') ?>
                            <?php $iCounter++ ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                            'href' => $arBlocks['FOOTER']['BUTTON']['LINK'],
                            'class' => [
                                'widget-footer-button',
                                'intec-ui' => [
                                    '',
                                    'size-5',
                                    'scheme-current',
                                    'control-button',
                                    'mod' => [
                                        'transparent',
                                        'round-half'
                                    ]
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
