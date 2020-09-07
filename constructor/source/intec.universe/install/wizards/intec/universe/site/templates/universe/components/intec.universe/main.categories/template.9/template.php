<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\FileHelper;
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


$bSlider = count($arResult['ITEMS']) > 1;

?>
<div class="widget c-categories c-categories-template-9" id="<?=$sTemplateId?>">
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
                        'widget-items' => true,
                        'owl-carousel' => $bSlider
                    ], true),
                    'data-role' => $bSlider ? 'slider' : 'false'
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
                                'width' => 600,
                                'height' => 600
                            ], BX_RESIZE_IMAGE_PROPORTIONAL);

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                    ?>
                        <div class="widget-item" id='<?= $sAreaId ?>'>
                            <div class="widget-item-wrapper intec-grid intec-grid-i-h-10 intec-grid-768-wrap">
                                <div class="intec-grid-item-2 intec-grid-item-768-1">
                                    <div class="widget-item-picture-wrap">
                                        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                            'class' => 'wwidget-item-picture',
                                            'alt' => $arItem['NAME'],
                                            'loading' => 'lazy',
                                            'data' => [
                                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                            ]
                                        ]) ?>
                                    </div>
                                    <?php if ($arVisual['MARKS']['USE']) { ?>
                                        <div class="widget-item-marks">
                                            <div class="widget-item-marks-name">
                                                <?= $arData['MARKS']['TITLE'] ?>
                                            </div>
                                            <div class="widget-item-marks-wrapper intec-grid intec-grid-wrap intec-grid-i-5">
                                                <?php foreach ($arData['MARKS']['VALUE'] as $sRedaction) { ?>
                                                    <div class="widget-item-mark-item-wrap intec-grid-item-auto">
                                                        <div class="widget-item-mark-item">
                                                            <?= $sRedaction ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="intec-grid-item-2 intec-grid-item-768-1">
                                    <div class="widget-item-name-wrap">
                                        <div class="widget-item-name">
                                            <?= $arItem['NAME'] ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($arItem['PREVIEW_TEXT'])) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arVisual['FEATURES']['USE'] && $arData['FEATURES']['SHOW']) {

                                        $sIcon = FileHelper::getFileData(__DIR__.'/svg/check.svg');

                                    ?>
                                        <div class="widget-item-features">
                                            <div class="widget-item-features-wrap intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-v-12 intec-grid-i-h-15">
                                                <?php foreach ($arData['FEATURES']['VALUE'] as $arValue) { ?>
                                                    <div class="intec-grid-item-2 intec-grid-item-768-1">
                                                        <div class="widget-item-features-item intec-grid intec-grid-a-v-center">
                                                            <div class="intec-grid-item-auto">
                                                                <div class="widget-item-features-item-icon">
                                                                    <?= $sIcon ?>
                                                                </div>
                                                            </div>
                                                            <div class="intec-grid-item intec-grid-item-768-1">
                                                                <div class="widget-item-features-item-name">
                                                                    <?= Html::decode($arValue['NAME']) ?>
                                                                </div>
                                                                <div class="widget-item-features-item-description">
                                                                    <?= Html::decode($arValue['DESCRIPTION']) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arVisual['PRICE']['USE'] && $arData['PRICE']['NEW']['SHOW']) { ?>
                                        <div class="widget-item-price">
                                            <?php if ($arData['PRICE']['NEW']) { ?>
                                                <div class="widget-item-price-value" data-price="new">
                                                    <?= $arData['PRICE']['NEW']['VALUE'] ?>
                                                </div>
                                            <?php } ?>
                                            <?php if ($arData['PRICE']['OLD']['VALUE']) { ?>
                                                <div class="widget-item-price-value" data-price="old">
                                                    <?= $arData['PRICE']['OLD']['VALUE'] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($arVisual['LINK']['USE'] && (!empty($arItem['DETAIL_PAGE_URL']) || $arData['LINK_ADDITIONAL']['SHOW'])) { ?>
                                        <div class="widget-item-buttons">
                                            <div class="widget-item-buttons-wrapper intec-grid intec-grid-wrap intec-grid-i-v-10 intec-grid-i-h-15">
                                                <?php if ($arData['LINK_ADDITIONAL']['SHOW']) { ?>
                                                    <div class="intec-grid-item-auto">
                                                        <?= Html::beginTag('a', [
                                                            'class' => [
                                                                'widget-item-button',
                                                                'widget-item-button-additional',
                                                                'intec-cl-background'
                                                            ],
                                                            'href' => $arData['LINK_ADDITIONAL']['VALUE'],
                                                            'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                                                        ]) ?>
                                                            <?= $arData['LINK_ADDITIONAL_TEXT']['VALUE'] ?>
                                                        <?= Html::endTag('a') ?>
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($arItem['DETAIL_PAGE_URL'])) { ?>
                                                    <div class="intec-grid-item-auto">
                                                        <?= Html::beginTag('a', [
                                                            'class' => [
                                                                'widget-item-button',
                                                                'widget-item-button-detail',
                                                                'intec-cl-border-hover'
                                                            ],
                                                            'href' => $arItem['DETAIL_PAGE_URL'],
                                                            'target' => $arVisual['LINK']['BLANK'] ? '_blank' : null
                                                        ]) ?>
                                                            <?= Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_9_BUTTON_LINK_TEXT') ?>
                                                        <?= Html::endTag('a') ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
        </div>
    </div>
</div>
<?php if ($bSlider) include(__DIR__.'/parts/script.php') ?>