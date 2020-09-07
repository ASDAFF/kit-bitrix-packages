<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

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
<div class="widget c-staff c-staff-template-2" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content intec-content-visible">
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
                        'a-v-start',
                        'a-h-center'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sPicture = $arItem['PREVIEW_PICTURE'];

                    if (empty($sPicture))
                        $sPicture = $arItem['DETAIL_PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 350,
                            'height' => 350
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sPicture))
                            $sPicture = $sPicture['src'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-element' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '1000-4' => $arVisual['COLUMNS'] >= 5,
                                '800-3' => $arVisual['COLUMNS'] >= 4,
                                '600-2' => $arVisual['COLUMNS'] >= 3,
                                '400-1' => true
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-element-wrapper" id="<?= $sAreaId ?>">
                            <div class="widget-element-image-wrap" >
                                <?= Html::tag('div', '', [
                                    'class' => 'widget-element-image',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                            </div>
                            <div class="widget-element-name">
                                <?= $arItem['NAME'] ?>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
            <?php if ($arVisual['BUTTON']['SHOW']) { ?>
                <div class="widget-button">
                    <a class="widget-button-wrapper intec-cl-text-hover" href="<?= $arVisual['BUTTON']['LINK'] ?>">
                        <div class="widget-button-text">
                            <div class="widget-button-name">
                                <?= Loc::getMessage('C_MAIN_STAFF_TEMPLATE_2_BUTTON_NAME') ?>
                            </div>
                            <div class="widget-button-description">
                                <?= Loc::getMessage('C_MAIN_STAFF_TEMPLATE_2_BUTTON_SECTION') ?>
                            </div>
                        </div>
                        <div class="widget-button-icon">
                            <i class="fal fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>