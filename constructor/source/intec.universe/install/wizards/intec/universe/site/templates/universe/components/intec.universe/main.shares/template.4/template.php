<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arHeader = ArrayHelper::getValue($arResult, 'HEADER_BLOCK');
$arDescription = ArrayHelper::getValue($arResult, 'DESCRIPTION_BLOCK');
$arFooter = ArrayHelper::getValue($arResult, 'FOOTER_BLOCK');
$arVisual = ArrayHelper::getValue($arResult, 'VISUAL');

$iElementCount = 1;
$arClasses = [];

$sElementTag = $arVisual['LINK_USE']?'a':'div';
?>
<?= Html::beginTag('div', [
    'class' => [
        'widget',
        'c-shares',
        'c-shares-template-3'
    ],
    'data' => [
        'column' => $arVisual['COLUMNS']
    ]
]) ?>
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arHeader['SHOW'] || $arDescription['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arHeader['SHOW']) { ?>
                        <div class="widget-title align-<?= $arHeader['POSITION'] ?>">
                            <?= $arHeader['TEXT'] ?>
                        </div>
                    <?php } ?>
                    <?php if ($arDescription['SHOW']) { ?>
                        <div class="widget-description align-<?= $arDescription['POSITION'] ?>">
                            <?= $arDescription['TEXT'] ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'widget-content',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-v-start',
                        'a-h-start',
                        'i-7'
                    ]
                ])
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sDetailPageUrl = ArrayHelper::getValue($arItem, 'DETAIL_PAGE_URL');

                    $arData = $arItem['DATA'];

                    $sName = ArrayHelper::getValue($arItem, 'NAME');

                    if (!empty($arData['TITLE']))
                        $sName = $arData['TITLE'];

                    if (!empty($arData['PICTURE'])) {
                        $sPicture = $arData['PICTURE'];
                    } elseif (!empty($arItem['PREVIEW_PICTURE'])) {
                        $sPicture = $arItem['PREVIEW_PICTURE'];
                    } else {
                        $sPicture = $arItem['DETAIL_PICTURE'];
                    }

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
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'widget-item-wrap',
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'],
                                '1100-2',
                                '720-1'
                            ]
                        ])
                    ]) ?>
                        <?= Html::beginTag($sElementTag,[
                            'class' => [
                                'widget-item',
                                'intec-cl-background-light'
                            ],
                            'id' => $sAreaId,
                            'href' => $arVisual['LINK_USE'] ? $sDetailPageUrl : null,
                            'style' => [
                                'background-color' => !empty($arData['BACKGROUND']) ? $arData['BACKGROUND'].'!important' : null,
                            ]
                        ])?>
                            <?= Html::tag('div', '', [
                                'class' => 'widget-item-picture',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                ]
                            ]) ?>
                            <div class="widget-item-text-wrap">
                                <div class="intec-aligner"></div>
                                <div class="widget-item-text">
                                    <?php if ($arData['STICK']['SHOW']) { ?>
                                        <?= Html::tag('div', $arData['STICK']['TEXT'],[
                                            'class' => [
                                                'widget-item-stick',
                                                'intec-cl-background-dark'
                                            ],
                                            'style' => [
                                                'background-color' => !empty($arData['STICK']['BACKGROUND']) ? $arData['STICK']['BACKGROUND'].'!important' : null,
                                            ]
                                        ])?>
                                    <?php } ?>
                                    <div class="widget-item-name">
                                        <?= $sName ?>
                                    </div>
                                </div>
                            </div>
                         <?= Html::endTag($sElementTag) ?>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
            <?php if ($arFooter['SHOW']) { ?>
                <div class="widget-footer align-<?= $arFooter['POSITION'] ?>">
                    <a class="widget-footer-all intec-cl-border intec-cl-background-hover" href="<?= $arFooter['LIST_PAGE'] ?>">
                        <?= $arFooter['TEXT'] ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>