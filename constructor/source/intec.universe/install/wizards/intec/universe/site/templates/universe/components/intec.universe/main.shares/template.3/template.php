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
$arCodes = ArrayHelper::getValue($arResult, 'PROPERTY_CODES');


$iElementCount = 1;
$arClasses = [];

$sElementTag = $arVisual['LINK_USE']?'a':'div';
?>
<div class="widget c-shares c-shares-template-3">
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

                    $sName = ArrayHelper::getValue($arItem, 'NAME');
                    $sDescription = ArrayHelper::getValue($arItem, 'PREVIEW_TEXT');
                    $sPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);
                    $sDetailPageUrl = ArrayHelper::getValue($arItem, 'DETAIL_PAGE_URL');

                    $arClasses = [
                        'widget-element-wrap',
                        'intec-grid-item' => [
                            $arVisual['LINE_COUNT'],
                            '1000-2',
                            '600-1'
                        ]
                    ];
                ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray($arClasses)
                    ]) ?>
                        <?= Html::beginTag($sElementTag,[
                            'class' => 'widget-element',
                            'id' => $sAreaId,
                            'href' => $arVisual['LINK_USE']?$sDetailPageUrl:null
                        ])?>
                            <?= Html::tag('div', '', [
                                'class' => 'widget-element-picture',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                ]
                            ]) ?>
                            <div class="widget-element-fade"></div>
                            <div class="widget-element-text">
                                <div class="widget-element-name">
                                    <div class="widget-element-name-wrapper">
                                        <div class="widget-element-name-wrapper-2">
                                            <?= $sName ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($arVisual['DESCRIPTION_USE']){ ?>
                                    <div class="widget-element-description">
                                        <?= $sDescription ?>
                                    </div>
                                <?php } ?>
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
</div>