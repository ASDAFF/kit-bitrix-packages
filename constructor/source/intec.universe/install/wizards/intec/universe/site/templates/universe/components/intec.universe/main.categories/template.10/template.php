<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
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
<div class="widget c-categories c-categories-template-10" id="<?=$sTemplateId?>">
    <div class="widget-wrapper intec-content intec-content-visible">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                <div class="widget-header">
                    <div class="intec-grid intec-grid-wrap intec-grid-i-25">
                        <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                            <div class="intec-grid-item intec-grid-item-600-1">
                                <div class="widget-title align-<?= $arBlocks['HEADER']['POSITION'] ?>">
                                    <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                            <div class="intec-grid-item-2 intec-grid-item-600-1">
                                <div class="widget-description align-<?= $arBlocks['DESCRIPTION']['POSITION'] ?>">
                                    <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                                </div>
                                <?php if ($arBlocks['DESCRIPTION']['LINK']['SHOW']) { ?>
                                    <div class="widget-description-link">
                                        <div class="widget-description-link-wrapper">
                                            <a class="widget-description-link-text intec-cl-text" href="<?= $arBlocks['DESCRIPTION']['LINK']['URL'] ?>">
                                                <?= $arBlocks['DESCRIPTION']['LINK']['TEXT'] ?>
                                            </a>
                                            <span class="widget-description-link-icon intec-cl-text far fa-chevron-right"></span>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-h-start',
                            'a-v-stretch'
                        ]
                    ])
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];
                        $sPicture = $arItem['PREVIEW_PICTURE'];

                        if (empty($sPicture))
                            $sPicture = $arItem['DETAIL_PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, [
                                'width' => 300,
                                'height' => 300
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        if ($arVisual['LINK']['USE'] && !empty($arItem['DETAIL_PAGE_URL']))
                            $sTag = 'a';
                        else
                            $sTag = 'div';

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    '' => true,
                                    $arVisual['COLUMNS'] => true,
                                    '1000-3' => $arVisual['COLUMNS'] > 3,
                                    '750-2' => $arVisual['COLUMNS'] > 2,
                                    '500-1' => $arVisual['COLUMNS'] > 1
                                ]
                            ], true)
                        ]) ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'widget-item-wrapper'
                                ],
                                'id' => $sAreaId,
                                'data-price' => $arVisual['PRICE']['USE'] ? 'true' : null
                            ]) ?>
                                <div class="widget-item-picture-wrap">
                                    <?= Html::tag($sTag, '', [
                                        'class' => 'widget-item-picture',
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
                                <div class="widget-item-marks">
                                    <?php if (!empty($arData['MARKS']['HIT'])) { ?>
                                        <div class="widget-item-mark" data-mark="hit">
                                            <?= Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_10_MARK_HIT_TEXT') ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arData['MARKS']['NEW'])) { ?>
                                        <div class="widget-item-mark" data-mark="new">
                                            <?= Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_10_MARK_NEW_TEXT') ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($arData['MARKS']['RECOMMEND'])) { ?>
                                        <div class="widget-item-mark" data-mark="recommend">
                                            <?= Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_10_MARK_RECOMMEND_TEXT') ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="widget-item-name-wrap">
                                    <?= Html::beginTag($sTag, [
                                        'class' => 'widget-item-name intec-cl-text-hover',
                                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'target' => $sTag === 'a' && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                                    ]) ?>
                                        <span><?= $arItem['NAME'] ?></span>
                                        <?php if (!empty($arData['TEXT'])) { ?>
                                            <span>
                                                <?= ' - '.$arData['TEXT'] ?>
                                            </span>
                                        <?php } ?>
                                    <?= Html::endTag($sTag) ?>
                                </div>
                                <?php if ($arVisual['PRICE']['USE']) { ?>
                                    <div class="widget-item-price">
                                        <?php if (!empty($arData['PRICE']['NEW'])) { ?>
                                            <div class="widget-item-price-value" data-price="new">
                                                <?= $arData['PRICE']['NEW'] ?>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($arData['PRICE']['OLD'])) { ?>
                                            <div class="widget-item-price-value" data-price="old">
                                                <?= $arData['PRICE']['OLD'] ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?= Html::endTag('div') ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>