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

?>
<div class="widget c-categories c-categories-template-2" id="<?= $sTemplateId ?>">
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
                <div class="widget-items intec-grid intec-grid-wrap intec-grid-i-v-20 intec-grid-i-h-15">
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];

                        if ($arVisual['PICTURE']['SHOW']) {
                            $sPicture = $arItem['PREVIEW_PICTURE'];

                            if (empty($sPicture))
                                $sPicture = $arItem['DETAIL_PICTURE'];

                            if (!empty($sPicture)) {
                                $sPicture = CFile::ResizeImageGet(
                                    $sPicture, [
                                    'width' => 200,
                                    'height' => 200
                                ],
                                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                                );

                                if (!empty($sPicture))
                                    $sPicture = $sPicture['src'];
                            }

                            if (empty($sPicture))
                                $sPicture = SITE_TEMPLATE_PATH . '/images/picture.missing.png';
                        }

                        if ($arVisual['LINK']['USE'] && !empty($arItem['DETAIL_PAGE_URL']))
                            $sTag = 'a';
                        else
                            $sTag = 'div';

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1024-2' => $arVisual['COLUMNS'] >= 2,
                                    '650-1' => true
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper intec-grid" id="<?= $sAreaId ?>">
                                <?php if ($arVisual['PICTURE']['SHOW']) { ?>
                                    <div class="widget-item-picture-wrapper intec-grid-item-auto">
                                        <?= Html::tag($sTag, '', [
                                            'class' => [
                                                'widget-item-picture',
                                                'intec-image-effect'
                                            ],
                                            'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                            'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ],
                                            'style' => [
                                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                            ]
                                        ]) ?>
                                    </div>
                                <?php } ?>
                                <div class="widget-item-text intec-grid-item">
                                    <?= Html::tag($sTag, $arItem['NAME'], [
                                        'class' => Html::cssClassFromArray([
                                            'widget-item-name' => true,
                                            'intec-cl-text' => $sTag !== 'a',
                                            'intec-cl-text-light-hover' => $sTag !== 'a'
                                        ], true),
                                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                    ]) ?>
                                    <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

