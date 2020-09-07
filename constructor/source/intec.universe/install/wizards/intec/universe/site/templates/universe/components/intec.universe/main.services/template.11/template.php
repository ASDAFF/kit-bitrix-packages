<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
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
    $arForm['BUTTON'] = Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_11_TEMPLATE_ODER_BUTTON_DEFAULT');

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

?>
<div class="widget c-services c-services-template-11" id="<?= $sTemplateId ?>">
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
                <?= Html::beginTag('div', [
                    'class' => [
                        'widget-items',
                        'intec-grid' => [
                            '',
                            'wrap',
                            'a-v-stretch',
                            'i-4'
                        ]
                    ],
                    'data' => [
                        'order' => $arForm['USE'] ? 'true' : 'false'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];
                        $arForm['PARAMETERS']['fields'][$arForm['FIELD']] = $arItem['NAME'];

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '1024-2' => $arVisual['COLUMNS'] >= 3,
                                    '768-1' => true
                                ]
                            ], true),
                            'data' => [
                                'price' => $arVisual['PRICE']['SHOW'] && !empty($arData['PRICE']) ? 'true' : 'false'
                            ]
                        ]) ?>
                            <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                <?php if ($arVisual['CATEGORY']['SHOW']) { ?>
                                    <div class="widget-item-category">
                                        <?php if (!empty($arData['CATEGORY'])) { ?>
                                            <?php if (Type::isArray($arData['CATEGORY'])) { ?>
                                                <?= implode(', ', $arData['CATEGORY']) ?>
                                            <?php } else { ?>
                                                <?= $arData['CATEGORY'] ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?= Html::tag($sTag, $arItem['NAME'], [
                                    'href' => $arVisual['LINK']['USE'] ? $arItem['DETAIL_PAGE_URL'] : null,
                                    'class' => Html::cssClassFromArray([
                                        'widget-item-name' => true,
                                        'intec-cl-text-hover' => $arVisual['LINK']['USE']
                                    ], true)
                                ]) ?>
                                <?php if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) { ?>
                                    <div class="widget-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE'])) { ?>
                                    <div class="widget-item-price">
                                        <?= $arData['PRICE'] ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arForm['USE']) { ?>
                                    <?= Html::tag('div', Html::stripTags($arForm['BUTTON']), [
                                        'class' => [
                                            'widget-item-button',
                                            'intec-cl-background',
                                            'intec-cl-background-light-hover'
                                        ],
                                        'onclick' => '(function() {
                                            universe.forms.show('.JavaScript::toObject($arForm['PARAMETERS']).');
                                            
                                            if (window.yandex && window.yandex.metrika) {
                                                window.yandex.metrika.reachGoal(\'forms.open\');
                                                window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['PARAMETERS']['id'].'.open').');
                                            }
                                        })()'
                                    ]) ?>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <?= Html::tag('a', $arBlocks['FOOTER']['BUTTON']['TEXT'], [
                            'href' => $arBlocks['FOOTER']['BUTTON']['LINK'],
                            'class' => [
                                'widget-footer-button',
                                'intec-ui' => [
                                    '',
                                    'size-5',
                                    'scheme-current',
                                    'control-button',
                                    'mod' => [
                                        'transparent',
                                        'round-half'
                                    ]
                                ]
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>