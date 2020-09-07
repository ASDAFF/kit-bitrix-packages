<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\regionality\models\Region;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$oContext = Context::getCurrent();
$sSite = $oContext->getSite();

if (empty($arResult['REGIONS']))
    return;

$oRegionCurrent = $arResult['REGION'];
$oRegionDefault = Region::getDefault();
$bHasChildren = count($arResult['REGIONS']) > 1;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'menu-item' => [
            '',
            'level-0',
            'extra',
            'regionality'
        ]
    ],
    'data' => [
        'role' => 'item',
        'level' => 0,
        'expanded' => 'false',
        'current' => 'false'
    ]
]) ?>
    <div class="menu-item-wrapper">
        <?= Html::beginTag('div', [
            'class' => [
                'menu-item-content',
                'intec-cl' => [
                    'text-hover'
                ]
            ]
        ]) ?>
            <div class="menu-information-button intec-grid intec-grid-a-v-center" data-action="menu.item.open">
                <div class="intec-grid-item-auto">
                    <i class="menu-information-icon fas fa-map-pin"></i>
                </div>
                <div class="intec-grid-item">
                    <span class="menu-item-text">
                        <?php $oFrame = $this->createFrame()->begin() ?>
                        <?= !empty($oRegionCurrent) ? Html::encode($oRegionCurrent->name) : Loc::getMessage('C_MENU_MOBILE_1_REGIONS_SELECT') ?>
                        <?php $oFrame->beginStub() ?>
                        <?= !empty($oRegionDefault) ? Html::encode($oRegionDefault->name) : Loc::getMessage('C_MENU_MOBILE_1_REGIONS_SELECT') ?>
                        <?php $oFrame->end() ?>
                    </span>
                </div>
            </div>
        <?= Html::endTag('div') ?>
        <div class="menu-item-items" data-role="items">
            <?= Html::beginTag('div', [
                'class' => [
                    'menu-item' => [
                        '',
                        'level-1',
                        'button'
                    ]
                ],
                'data' => [
                    'level' => 1
                ]
            ]) ?>
                <div class="menu-item-wrapper">
                    <div class="menu-item-content intec-cl-text-hover" data-action="menu.item.close">
                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                            <div class="menu-item-icon-wrap intec-grid-item-auto">
                                <div class="menu-item-icon">
                                    <i class="far fa-angle-left"></i>
                                </div>
                            </div>
                            <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                <div class="menu-item-text">
                                    <?= Loc::getMessage('C_MENU_MOBILE_2_BACK') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
            <?php if ($bHasChildren) { ?>
                <?php foreach ($arResult['REGIONS'] as $oRegion) { ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'menu-item' => [
                                '',
                                'level-1',
                                'button'
                            ]
                        ],
                        'data' => [
                            'action' => 'menu.close',
                            'role' => 'item',
                            'region' => $oRegion->id,
                            'level' => 1
                        ]
                    ]) ?>
                        <div class="menu-item-wrapper">
                            <div class="menu-item-content intec-cl-text-hover" data-action="menu.close">
                                <?= Html::encode($oRegion->name) ?>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <?php $oFrame = $this->createFrame()->begin() ?>
        <script type="text/javascript">
            (function ($) {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var regions = $('[data-role="item"][data-region]', root);
                var component = new JCIntecRegionalityRegionsSelect(<?= JavaScript::toObject([
                    'action' => $arResult['ACTION'],
                    'site' => $sSite
                ]) ?>);

                regions.on('click', function () {
                    var region = $(this);
                    var id = region.data('region');

                    component.select(id);
                })
            })(jQuery);
        </script>
    <?php $oFrame->end() ?>
<?= Html::endTag('div') ?>