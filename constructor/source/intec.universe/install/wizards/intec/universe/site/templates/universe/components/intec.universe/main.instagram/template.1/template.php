<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
$arViewParams = ArrayHelper::getValue($arResult, 'VIEW_PARAMETERS');

?>
<div class="widget c-instagram c-instagram-template-1" id="<?= $sTemplateId ?>">
    <?php if (!$arViewParams['WIDE']) { ?>
        <div class="widget-wrapper intec-content">
            <div class="widget-wrapper-2 intec-content-wrapper">
    <?php } ?>
            <?php if ($arHeader['SHOW']) { ?>
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
            <div class="widget-content">
                <div class="widget-elements<?= $arViewParams['PADDING_USE'] ? ' widget-elements-with-padding' : '' ?>">
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sImage = $arItem['IMAGES']['standard_resolution']['url'];
                            $sDescription = ArrayHelper::getValue($arItem, 'DESCRIPTION');
                            $sLink = ArrayHelper::getValue($arItem, 'LINK');

                        ?>
                        <div class="widget-element grid-<?= $arViewParams['LINE_COUNT'] ?>">
                            <div class="widget-element-wrapper">
                                <?= Html::beginTag('a', [
                                    'class' => [
                                        'widget-element-image'
                                    ],
                                    'target' => '_blank',
                                    'href' => $sLink,
                                    'data' => [
                                        'lazyload-use' => $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arResult['LAZYLOAD']['USE'] ? $sImage : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arResult['LAZYLOAD']['USE'] ? 'url(\''.$sImage.'\')' : null
                                    ]
                                ]) ?>
                                    <div class="widget-element-description">
                                        <?php if ($arViewParams['DESCRIPTION_ITEM_SHOW']) { ?>
                                            <?= TruncateText($sDescription, '200') ?>
                                        <?php } ?>
                                    </div>
                                <?= Html::endTag('a') ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($arFooter['SHOW']) { ?>
                <div class="widget-footer align-<?= $arFooter['POSITION'] ?>">
                    <a class="widget-footer-all intec-cl-border intec-cl-background-hover" href="<?= $arFooter['LIST_PAGE'] ?>">
                        <?= $arFooter['TEXT'] ?>
                    </a>
                </div>
            <?php } ?>
    <?php if (!$arViewParams['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>