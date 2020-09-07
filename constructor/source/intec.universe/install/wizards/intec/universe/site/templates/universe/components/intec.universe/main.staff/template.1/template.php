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

$arVisual['SOCIALS']['LIST']['VKONTAKTE']['ICON'] = 'fab fa-vk';
$arVisual['SOCIALS']['LIST']['FACEBOOK']['ICON'] = 'fab fa-facebook-f';
$arVisual['SOCIALS']['LIST']['INSTAGRAM']['ICON'] = 'fab fa-instagram';
$arVisual['SOCIALS']['LIST']['TWITTER']['ICON'] = 'fab fa-twitter';

?>
<div class="widget c-staff c-staff-template-1" id="<?= $sTemplateId ?>">
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
                            'width' => 250,
                            'height' => 250
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
                                '1150-4' => $arVisual['COLUMNS'] >= 5,
                                '950-3' => $arVisual['COLUMNS'] >= 4,
                                '700-2' => $arVisual['COLUMNS'] >= 3,
                                '500-1' => true
                            ]
                        ], true)
                    ]) ?>
                        <div class="widget-element-wrapper" id="<?= $sAreaId ?>">
                            <div class="widget-element-image-wrap">
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
                            <div class="widget-element-name intec-cl-text">
                                <?= $arItem['NAME'] ?>
                            </div>
                            <?php if ($arVisual['POSITION']['SHOW'] && !empty($arItem['POSITION'])) { ?>
                                <div class="widget-element-position">
                                    <?= $arItem['POSITION'] ?>
                                </div>
                            <?php } ?>
                            <?php if ($arVisual['SOCIALS']['SHOW']) { ?>
                                <?= Html::beginTag('div', [ /** Контейнер для иконок соц. сетей */
                                    'class' => [
                                        'widget-element-icons',
                                        'intec-grid' => [
                                            '',
                                            'wrap',
                                            'a-v-start',
                                            'a-h-center'
                                        ]
                                    ]
                                ]) ?>
                                    <?php foreach ($arVisual['SOCIALS']['LIST'] as $arSocial) { ?>
                                        <?php $sLink = $arItem['SOCIALS'][$arSocial['CODE']] ?>
                                        <?php if (!empty($sLink)) { ?>
                                            <div class="widget-element-icon-wrap intec-grid-item-4">
                                                <?= Html::tag('a', '', [
                                                    'href' => $sLink,
                                                    'target' => '_blank',
                                                    'title' => $arSocial['NAME'],
                                                    'class' => [
                                                        'widget-element-icon',
                                                        'intec-cl-text-hover',
                                                        $arSocial['ICON']
                                                    ]
                                                ]) ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        </div>
    </div>
</div>