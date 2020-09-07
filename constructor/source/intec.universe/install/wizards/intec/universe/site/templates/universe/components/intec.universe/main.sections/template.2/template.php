<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['SECTIONS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

?>
<div class="widget c-sections c-sections-template-2" id="<?= $sTemplateId ?>">
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
                        'a-v-stretch',
                        'a-h-center'
                    ]
                ]
            ]) ?>
                <?php foreach ($arResult['SECTIONS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sPicture = $arItem['PICTURE'];

                    if (!empty($sPicture)) {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 450,
                            'height' => 450
                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                        if (!empty($sPicture))
                            $sPicture = $sPicture['src'];
                    }

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-element-wrap' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '768-2' => $arVisual['COLUMNS'] >= 3,
                                '550-1' => true
                            ]
                        ], true)
                    ]) ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => [
                                'widget-element',
                                'intec-grid' => [
                                    '',
                                    'a-v-stretch',
                                    'a-h-center'
                                ]
                            ]
                        ]) ?>
                            <div class="widget-element-picture-wrap intec-grid-item-auto">
                                <?= Html::tag('a', '', [
                                    'href' => $arItem['SECTION_PAGE_URL'],
                                    'class' => [
                                        'widget-element-picture',
                                        'intec-image-effect'
                                    ],
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                            </div>
                            <div class="widget-element-text intec-grid-item">
                                <a href="<?= $arItem['SECTION_PAGE_URL'] ?>" class="widget-element-name intec-cl-text-hover">
                                    <?= $arItem['NAME'] ?>
                                </a>
                                <?php if ($arVisual['CHILDREN']['SHOW'] && !empty($arItem['SECTIONS'])) { ?>
                                    <div class="widget-element-section">
                                        <?php $iCount = 0 ?>
                                        <?php foreach ($arItem['SECTIONS'] as $arSection) {

                                            ++$iCount;

                                            if ($arVisual['CHILDREN']['COUNT'] !== null && $iCount > $arVisual['CHILDREN']['COUNT'])
                                                break;

                                        ?>
                                            <div class="widget-element-section-element">
                                                <?= Html::tag('a', $arSection['NAME'], [
                                                    'class' => [
                                                        'widget-element-section-name',
                                                        'intec-cl-text-hover'
                                                    ],
                                                    'href' => $arSection['SECTION_PAGE_URL']
                                                ]) ?>
                                                <?php if ($arVisual['QUANTITY']['SHOW']) { ?>
                                                    <span class="widget-element-section-count">
                                                        <?= $arSection['ELEMENT_CNT'] ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>