<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
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

$iCounter = 0;

?>
<div class="widget c-faq c-faq-template-3" id="<?= $sTemplateId ?>">
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
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => 'widget-content-wrapper',
                    'data' => [
                        'role' => 'container',
                        'expanded' => 'false'
                    ]
                ]) ?>
                    <div class="widget-items">
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $iCounter++;

                            $sAnswer = $arItem['PREVIEW_TEXT'];

                            if (empty($sAnswer))
                                $sAnswer = $arItem['DETAIL_TEXT'];

                            if (empty($sAnswer))
                                continue;

                        ?>
                            <?= Html::beginTag('div', [
                                'class' => 'widget-item',
                                'data' => [
                                    'role' => 'item',
                                    'expanded' => $arItem['DATA']['EXPANDED'] ? 'true' : 'false',
                                    'action' => $arVisual['HIDE'] && $iCounter > 4 ? 'hide' : 'none'
                                ],
                                'style' => [
                                    'display' => $arVisual['HIDE'] && $iCounter > 4 ? 'none' : null
                                ]
                            ]) ?>
                                <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'widget-item-name-wrap',
                                            'intec-grid',
                                            'intec-grid-a-v-center'
                                        ],
                                        'data' => [
                                            'role' => 'item.button'
                                        ]
                                    ]) ?>
                                        <div class="intec-grid-item">
                                            <div class="widget-item-name intec-cl-text-hover">
                                                <?= $arItem['NAME'] ?>
                                            </div>
                                        </div>
                                        <div class="widget-item-auto">
                                            <div class="widget-item-icon">
                                                <?php include(__DIR__.'/svg/arrow.svg') ?>
                                            </div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                    <div class="widget-item-description-wrap" data-role="item.content">
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                    </div>
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
                    <?php if ($arVisual['HIDE']) { ?>
                        <?= Html::tag('div', Loc::getMessage('C_MAIN_FAQ_TEMPLATE_3_TEMPLATE_BUTTON_SHOW'), [
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
                            ],
                            'data' => [
                                'role' => 'button'
                            ]
                        ]) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>