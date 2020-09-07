<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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
<?= Html::beginTag('div', [
    'class' => [
        'widget',
        'c-advantages',
        'c-advantages-template-12'
    ],
    'id' => $sTemplateId,
    'data' => [
        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
        'original' => $arVisual['LAZYLOAD']['USE'] && !empty($arVisual['BACKGROUND']['PATH']) ? $arVisual['BACKGROUND']['PATH'] : null
    ],
    'style' => [
        'background-image' => !$arVisual['LAZYLOAD']['USE'] && !empty($arVisual['BACKGROUND']['PATH']) ? 'url(\''.$arVisual['BACKGROUND']['PATH'].'\')' : null,
        'background-color' => '#17171d'
    ],
    'data-number-show' => $arVisual['NUMBER']['SHOW'] ? 'true' : 'false'
]) ?>
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <div class="widget-content">
                <div class="widget-items intec-grid intec-grid-wrap intec-grid-a-v-stretch intec-grid-a-h-start intec-grid-i-23">
                    <?php if ($arBlocks['HEADER']['SHOW'] || $arBlocks['DESCRIPTION']['SHOW']) { ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '768-2' => $arVisual['COLUMNS'] >= 3,
                                    '600-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-header">
                                <?php if ($arBlocks['HEADER']['SHOW']) { ?>
                                    <div class="widget-title">
                                        <?= Html::encode($arBlocks['HEADER']['TEXT']) ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arBlocks['DESCRIPTION']['SHOW']) { ?>
                                    <div class="widget-description">
                                        <?= Html::encode($arBlocks['DESCRIPTION']['TEXT']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $iCounter++;
                    ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => true,
                                    '768-2' => $arVisual['COLUMNS'] >= 3,
                                    '600-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper">
                                <div class="widget-item-name-wrap">
                                    <?php if ($arVisual['NUMBER']['SHOW']) { ?>
                                        <div class="widget-item-counter">
                                            <?= $iCounter ?>.
                                        </div>
                                    <?php } ?>
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                </div>
                                <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                    <div class="widget-item-description">
                                        <?= $arItem['PREVIEW_TEXT'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>