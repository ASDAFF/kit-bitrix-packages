<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);


if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arHeader = $arResult['BLOCKS']['HEADER'];
$arDescription = $arResult['BLOCKS']['DESCRIPTION'];
$arVisual = $arResult['VISUAL'];
$arCodes = $arResult['PROPERTY_CODES'];

$iCounter = 0;
$sBlockBackground = '';
?>
    <div class="widget c-schedule c-schedule-template-1">
        <?php if ($arHeader['SHOW'] || $arDescription['SHOW']) { ?>
            <div class="widget-header intec-content">
                <div class="intec-content-wrapper">
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
            </div>
        <?php } ?>
        <div class="widget-content">
            <?php foreach ($arResult['ITEMS'] as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $bStaffShow = false;
                $bTextShow = false;
                $bFileShow = false;

                $arTime = [];
                $arEmployee = [];
                $arText = [];
                $arFile = [];

                $iCounter++;
                $sBlockBackground = $iCounter % 2 == 1 ? $arVisual['LINES']['FIRST'] : $arVisual['LINES']['SECOND'];

                if ($arVisual['TIME']['SHOW']) {
                    $arTime = [
                        'FROM' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['TIME']['FROM'], 'VALUE']),
                        'TO' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['TIME']['TO'], 'VALUE'])
                    ];

                    $arVisual['TIME']['SHOW'] = !empty($arTime['FROM']);
                }

                if ($arVisual['STAFF']['SHOW']) {
                    $arEmployee = ArrayHelper::getValue($arItem, ['DATA', 'EMPLOYEE']);

                    if (!empty($arEmployee))
                        $bStaffShow = true;

                    if (!empty($arEmployee['PREVIEW_PICTURE']))
                        $arEmployee['PICTURE'] = $arEmployee['PREVIEW_PICTURE'];
                    else if (!empty($arEmployee['DETAIL_PICTURE']))
                        $arEmployee['PICTURE'] = $arEmployee['DETAIL_PICTURE'];

                    if (!empty($arEmployee['PICTURE'])) {
                        $arImageResize = CFile::ResizeImageGet(
                            $arEmployee['PICTURE'],
                            [
                                'width' => 100,
                                'height' => 100
                            ],
                            BX_RESIZE_IMAGE_EXACT
                        );
                        $arEmployee['PICTURE'] = $arImageResize['src'];
                    }
                }

                if ($arVisual['TEXT']['SHOW']) {
                    $arText = ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['TEXT'], 'VALUE']);

                    if (!empty($arText))
                        $bTextShow = true;
                }

                if ($arVisual['FILE']['SHOW']) {
                    $arFile = ArrayHelper::getValue($arItem, ['DATA', 'FILE']);

                    if (!empty($arFile))
                        $bFileShow = true;
                }

            ?>
                <div class="widget-element-wrap" data-background="<?= $sBlockBackground ?>">
                    <div class="intec-content intec-content-visible">
                        <div class="intec-content-wrapper">
                            <div class="widget-element" id="<?= $sAreaId ?>">
                                <?php if ($arVisual['TIME']['SHOW']) { ?>
                                    <div class="widget-element-time">
                                        <?php if (!empty($arTime['TO']) && $arVisual['TIME']['FORMAT'] != 'from') { ?>
                                            <?php if ($arVisual['TIME']['FORMAT'] == 'from-to-1') { ?>

                                                <?= Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM_TO_1_REPLACE', [
                                                    '#FROM#' => $arTime['FROM'],
                                                    '#TO#' => $arTime['TO']
                                                ]) ?>
                                            <?php } else if ($arVisual['TIME']['FORMAT'] == 'from-to-2') {?>
                                                <?= Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM_TO_2_REPLACE', [
                                                    '#FROM#' => $arTime['FROM'],
                                                    '#TO#' => $arTime['TO']
                                                ]) ?>
                                            <?php } else if ($arVisual['TIME']['FORMAT'] == 'to') { ?>
                                                <?= Loc::getMessage('C_SCHEDULE_TEMP1_TIME_TO_REPLACE', [
                                                    '#TO#' => $arTime['TO']
                                                ]) ?>
                                            <?php } ?>
                                        <? } else if ($arVisual['TIME']['FORMAT'] == 'from' || empty($arTime['FROM'])) { ?>
                                            <?= Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM_REPLACE', [
                                                '#FROM#' => $arTime['FROM']
                                            ]) ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="widget-element-name intec-cl-text">
                                    <?= $arItem['NAME'] ?>
                                </div>
                                <?php if ($bStaffShow || $bTextShow) { ?>
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'widget-element-content',
                                            'intec-grid' => [
                                                '',
                                                'wrap',
                                                'a-h-center'
                                            ]
                                        ],
                                        'data-staff' => !$bStaffShow ? 'false' : null,
                                        'data-text' => !$bTextShow ? 'false' : null
                                    ]) ?>
                                        <?php if ($bStaffShow) { ?>
                                            <?= Html::beginTag('div', [
                                                'class' => [
                                                    'widget-element-staff-container',
                                                    'intec-grid-item-2',
                                                    'intec-grid-item-750-1'
                                                ],
                                                'data-staff-picture' => !$arVisual['STAFF']['SHOW_PICTURE'] ? 'false' : null
                                            ]) ?>
                                                <div class="widget-element-staff-wrap">
                                                    <div class="widget-element-staff intec-grid intec-grid-a-v-center">
                                                        <?php if ($arVisual['STAFF']['SHOW_PICTURE'] && !empty($arEmployee['PICTURE'])) { ?>
                                                            <?= Html::tag('div', '', [
                                                                'class' => [
                                                                    'widget-element-staff-picture',
                                                                    'intec-grid-item-auto',
                                                                ],
                                                                'data' => [
                                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $arEmployee['PICTURE'] : null
                                                                ],
                                                                'style' => [
                                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arEmployee['PICTURE'].'\')' : null
                                                                ]
                                                            ]) ?>
                                                        <?php } ?>
                                                        <div class="widget-element-staff-info intec-grid-item">
                                                            <div class="widget-element-staff-name">
                                                                <?= $arEmployee['NAME'] ?>
                                                            </div>
                                                            <?php if ($arVisual['STAFF']['SHOW_POSITION'] && !empty($arEmployee['DATA']['POSITION']['VALUE'])) { ?>
                                                                <div class="widget-element-staff-position">
                                                                    <?= $arEmployee['DATA']['POSITION']['VALUE'] ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?= Html::endTag('div') ?>
                                        <?php } ?>
                                        <?php if ($bTextShow) { ?>
                                            <div class="widget-element-text-container intec-grid-item-2 intec-grid-item-750-1">
                                                <div class="widget-element-text">
                                                    <?php if (Type::isArray($arText)) { ?>
                                                        <ul class="widget-element-text-list intec-ui-mod-simple">
                                                            <?php foreach ($arText as $sText) { ?>
                                                                <li class="widget-element-text-element">
                                                                    <?= $sText ?>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php }  else { ?>
                                                        <div class="widget-element-text-element">
                                                            <?= "- $arText" ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                                <?php if ($bFileShow) { ?>
                                    <div class="widget-element-file-container">
                                        <a class="widget-element-file" href="<?= $arFile['SRC'] ?>" download>
                                            <i class="widget-element-file-icon far fa-arrow-down"></i>
                                            <span class="widget-element-file-text">
                                                <?= $arVisual['FILE']['TEXT'] ?>
                                            </span>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>


