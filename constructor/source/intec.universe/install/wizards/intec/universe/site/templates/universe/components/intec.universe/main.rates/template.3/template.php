<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$arForm = $arResult['FORM'];
$arForm['PARAMETERS'] = [
    'id' => $arForm['ID'],
    'template' => $arForm['TEMPLATE'],
    'parameters' => [
        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ORDER',
        'CONSENT_URL' => $arForm['CONSENT']
    ],
    'settings' => [
        'title' => $arForm['TITLE']
    ],
    'fields' => [
        $arForm['FIELD'] => null
    ]
];

if (empty($arForm['BUTTON']))
    $arForm['BUTTON'] = Loc::getMessage('C_MAIN_RATES_TEMPLATE_3_ORDER_BUTTON_DEFAULT')

?>
<div class="widget c-rates c-rates-template-3" id="<?= $sTemplateId ?>">
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
            <div class="widget-content">
                <div class="widget-items-wrap">
                    <div class="widget-items">
                        <div class="widget-item widget-item-head">
                            <div class="widget-item-head-property-name">
                                <?= Loc::getMessage('C_MAIN_RATES_TEMPLATE_3_HEAD_COLUMN_1'); ?>
                            </div>
                            <?php foreach($arResult['ITEMS'] as $arItem) {

                                $sId = $sTemplateId.'_'.$arItem['ID'];
                                $sAreaId = $this->GetEditAreaId($sId);
                                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);
                            ?>
                                <div class="widget-item-head-property" id="<?= $sAreaId ?>">
                                    <?= $arItem['NAME'] ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php foreach($arResult['PROPERTIES'] as $arProperty) { ?>
                            <div class="widget-item">
                                <div class="widget-item-property-name">
                                    <?= $arProperty['NAME'] ?>
                                </div>
                                <?php foreach ($arResult['ITEMS'] as $arItem) {

                                    $sPropertyValueCode = ArrayHelper::getValue($arItem, ['DISPLAY_PROPERTIES', $arProperty['CODE'], 'VALUE_XML_ID']);
                                    $mPropertyValue = ArrayHelper::getValue($arItem, ['DISPLAY_PROPERTIES', $arProperty['CODE'], 'DISPLAY_VALUE']);
                                    
                                ?>
                                    <div class="widget-item-property">
                                        <div class="widget-item-head-mobile">
                                            <?= $arItem['NAME'] ?>
                                        </div>
                                        <div class="widget-item-text">
                                            <?php if (!empty($mPropertyValue)) { ?>
                                                <?php if ($arProperty['PROPERTY_TYPE'] !== 'L' || $arProperty['LIST_TYPE'] !== 'C' || Type::isArray($mPropertyValue)) { ?>
                                                    <?php if (!Type::isArray($mPropertyValue)) { ?>
                                                        <?= $mPropertyValue ?>
                                                    <?php } else { ?>
                                                        <?= implode(', ', $mPropertyValue) ?>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php if ($sPropertyValueCode === 'Y') { ?>
                                                        <span class="icon-available"></span>
                                                    <?php } else if ($sPropertyValueCode === 'N') { ?>
                                                        <span class="icon-unavailable"></span>
                                                    <?php } else { ?>
                                                        <?= $mPropertyValue ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <span class="icon-unavailable"></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($arVisual['PRICE']['SHOW']) { ?>
                            <div class="widget-item widget-item-price">
                                <div class="widget-item-property-name">
                                    <?= Loc::getMessage('C_MAIN_RATES_TEMPLATE_3_RRICE_TITLE') ?>
                                </div>
                                <?php foreach($arResult['ITEMS'] as $arItem) { ?>
                                    <div class="widget-item-property">
                                        <span>
                                            <?= $arItem['DATA']['PRICE'] ?>
                                        </span>
                                        <?php if (!empty($arItem['DATA']['CURRENCY'])) { ?>
                                            <span>
                                                <?= $arItem['DATA']['CURRENCY'] ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($arForm['USE']) { ?>
                            <div class="widget-item widget-item-button-wrap">
                                <div class="widget-item-property-name"></div>
                                <?php foreach($arResult['ITEMS'] as $arItem) { ?>
                                    <?php
                                    $arForm['PARAMETERS']['fields'][$arForm['FIELD']] = $arItem['NAME'];
                                    ?>
                                    <div class="widget-item-property">
                                        <?= Html::tag('div', Html::stripTags($arForm['BUTTON']), [
                                            'class' => [
                                                'widget-item-button',
                                                'intec-ui' => [
                                                    '',
                                                    'control-button',
                                                    'scheme-current',
                                                    'mod-block'
                                                ]
                                            ],
                                            'onclick' => '(function() {
                                                universe.forms.show('.JavaScript::toObject($arForm['PARAMETERS']).');
                                                if (window.yandex && window.yandex.metrika) {
                                                   window.yandex.metrika.reachGoal(\'forms.open\');
                                                   window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['ID'].'.open').');
                                               }
                                            })()'
                                        ]) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>