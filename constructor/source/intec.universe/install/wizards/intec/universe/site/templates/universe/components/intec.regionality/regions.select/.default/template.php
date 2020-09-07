<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
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

/** @var Region $oRegionCurrent */
$oRegionCurrent = $arResult['REGION'];
$oRegionDefault = Region::getDefault();

?>
<?php $oFrame = $this->createFrame()->begin() ?>
<div id="<?= $sTemplateId ?>" class="ns-intec-regionality c-regions-select c-regions-select-default">
    <div class="regions-select-region intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-1 intec-cl-text-light-hover" data-role="select">
        <?php if (!empty($oRegionCurrent)) { ?>
            <span class="regions-select-region-text intec-grid-item-auto">
                <?= Html::encode($oRegionCurrent->name) ?>
            </span>
            <span class="regions-select-region-icon intec-grid-item-auto">
                <i class="far fa-chevron-down"></i>
            </span>
        <?php } else { ?>
            <span class="regions-select-region-text intec-grid-item-auto"><?= Loc::getMessage('C_REGIONS_SELECT_DEFAULT_REGION_UNSET') ?></span>
            <span class="regions-select-region-icon intec-grid-item-auto">
                <i class="far fa-chevron-down"></i>
            </span>
        <?php } ?>
    </div>
    <div class="regions-select-dialog" data-role="dialog">
        <div class="regions-select-dialog-window">
            <div class="regions-select-dialog-window-content">
                <div class="regions-select-dialog-search">
                    <?= Html::textInput(null, null, [
                        'class' => [
                            'regions-select-dialog-search-input',
                            'intec-ui' => [
                                '',
                                'control-input',
                                'mod-block',
                                'mod-round-half',
                                'size-2'
                            ]
                        ],
                        'placeholder' => Loc::getMessage('C_REGIONS_SELECT_DEFAULT_DIALOG_SEARCH_PLACEHOLDER'),
                        'data' => [
                            'role' => 'dialog.search'
                        ]
                    ]) ?>
                </div>
                <?php if (!empty($oRegionCurrent) && !$arResult['SELECTED']) { ?>
                    <div class="regions-select-dialog-auto intec-cl-text-hover intec-cl-border-hover" data-role="dialog.auto" data-region="<?= $oRegionCurrent->id ?>">
                        <?= Loc::getMessage('C_REGIONS_SELECT_DEFAULT_DIALOG_AUTO') ?>
                    </div>
                <?php } ?>
                <div class="regions-select-dialog-regions scrollbar-inner" data-role="dialog.regions">
                    <?php foreach ($arResult['REGIONS'] as $oRegion) { ?>
                        <?php if ($oRegion === $oRegionCurrent) continue ?>
                        <div class="regions-select-dialog-region" data-id="<?= $oRegion->id ?>" data-role="dialog.region">
                            <div class="regions-select-dialog-region-selector intec-cl-text-hover" data-role="dialog.region.selector">
                                <?= Html::encode($oRegion->name) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($oRegionCurrent) && !$arResult['SELECTED'] && !defined('EDITOR')) { ?>
        <div class="regions-select-question" data-role="question" data-region="<?= $oRegionCurrent->id ?>">
            <div class="regions-select-question-title-wrap">
                <div class="regions-select-question-title">
                    <?= Loc::getMessage('C_REGIONS_SELECT_DEFAULT_QUESTION_TEXT') ?>
                </div>
                <div class="regions-select-question-name">
                    <?= Html::encode($oRegionCurrent->name) ?>
                </div>
            </div>
            <div class="regions-select-question-buttons">
                <button class="regions-select-question-button intec-cl-background intec-cl-background-light-hover" data-role="question.yes">
                    <?= Loc::getMessage('C_REGIONS_SELECT_DEFAULT_QUESTION_BUTTONS_YES') ?>
                </button>
                <button class="regions-select-question-button" data-role="question.no">
                    <?= Loc::getMessage('C_REGIONS_SELECT_DEFAULT_QUESTION_BUTTONS_NO') ?>
                </button>
            </div>
            <div class="regions-select-question-close" data-role="question.close">
                <i class="fal fa-times intec-cl-text-hover"></i>
            </div>
        </div>
    <?php } ?>
    <script type="text/javascript">
        (function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var data;
            var dialog = $('[data-role="dialog"]', root);
            var select = $('[data-role="select"]', root);
            var window;
            var question = $('[data-role="question"]', root);
            var component = new JCIntecRegionalityRegionsSelect(<?= JavaScript::toObject([
                'action' => $arResult['ACTION'],
                'site' => $sSite
            ]) ?>);

            dialog.search = $('[data-role="dialog.search"]', dialog);
            dialog.auto = $('[data-role="dialog.auto"]', dialog);
            dialog.regionsContainer = $('[data-role="dialog.regions"]', dialog);
            dialog.regionsContainer.scrollbar();
            dialog.regions = $('[data-role="dialog.region"]', dialog.regionsContainer);
            dialog.open = function () {
                window.show();
            };

            dialog.close = function () {
                window.close();
            };

            data = <?= JavaScript::toObject([
                'id' => $sTemplateId.'-dialog',
                'title' => Loc::getMessage('C_REGIONS_SELECT_DEFAULT_DIALOG_TITLE')
            ]) ?>;

            question.yes = $('[data-role="question.yes"]', question);
            question.no = $('[data-role="question.no"]', question);
            question.close = function () {
                question.remove();
            };

            window = new BX.PopupWindow(data.id, null, {
                'content': null,
                'title': data.title,
                'className': 'regions-select-popup regions-select-popup-default',
                'closeIcon': {
                    'right': '35px',
                    'top': '22px'
                },
                'zIndex': 0,
                'offsetLeft': 0,
                'offsetTop': 0,
                'width': null,
                'overlay': true,
                'titleBar': {
                    'content': BX.create('span', {
                        'html': data.title,
                        'props': {
                            'className': 'access-title-bar'
                        }
                    })
                }
            });

            window.setContent(dialog.get(0));

            select.on('click', function () {
                dialog.open();
                question.close();
            });

            dialog.search.on('keyup', function () {
                var query = this.value;
                var expression = new RegExp(query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'i');

                dialog.regions.each(function () {
                    var region = $(this);
                    var selector = $('[data-role="dialog.region.selector"]', region);

                    if (query.length === 0 || selector.html().match(expression)) {
                        region.css('display', '');
                    } else {
                        region.css('display', 'none');
                    }
                });
            });

            dialog.auto.on('click', function () {
                var id = dialog.auto.data('region');

                component.select(id);
                dialog.auto.remove();
                dialog.close();
            });

            dialog.regions.on('click', function () {
                var region = $(this);
                var id = region.data('id');

                component.select(id);
                dialog.close();
            });

            question.yes.on('click', function () {
                var id = question.data('region');

                question.close();
                component.select(id);
            });

            question.no.on('click', function () {
                question.close();
                dialog.open();
            });

            $('[data-role="question.close"]', question).on('click', function () {
                var id = question.data('region');

                question.close();
                component.select(id);
            });
        })();
    </script>
</div>
<?php $oFrame->beginStub() ?>
<div class="ns-intec-regionality c-regions-select c-regions-select-default">
    <div class="regions-select-region intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-1 intec-cl-text intec-cl-text-light-hover" data-role="select">
        <span class="regions-select-region-text intec-grid-item-auto">
            <?= !empty($oRegionDefault) ? Html::encode($oRegionDefault->name) : Loc::getMessage('C_REGIONS_SELECT_DEFAULT_REGION_UNSET') ?>
        </span>
        <span class="regions-select-region-icon intec-grid-item-auto">
            <i class="far fa-chevron-down"></i>
        </span>
    </div>
</div>
<?php $oFrame->end() ?>