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
<div class="widget c-advantages c-advantages-template-23" id="<?= $sTemplateId ?>">
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
                    'class' => [
                        'widget-items',
                        'intec-grid',
                        'intec-grid-wrap',
                        'intec-grid-a-h-start',
                        'intec-grid-a-v-stretch',
                        'intec-grid-i-h-25',
                        'intec-grid-i-v-10',
                    ],
                    'data' => [
                        'number-show' => $arVisual['NUMBER']['SHOW'] ? 'true' : 'false',
                        'role' => 'items',
                        'state' => 'collapsed'
                    ]
                ]) ?>
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
                                    '1024-3' => $arVisual['COLUMNS'] >= 4,
                                    '768-2' => $arVisual['COLUMNS'] >= 3,
                                    '600-1' => $arVisual['COLUMNS'] >= 2
                                ]
                            ], true),
                            'data' => [
                                'role' => 'item',
                                'action' => $arVisual['HIDE'] && $iCounter > $arVisual['COLUMNS'] ? 'hide' : 'show'
                            ]
                        ]) ?>
                            <div class="widget-item-wrapper">
                                <?php if ($arVisual['NUMBER']['SHOW']) { ?>
                                    <div class="widget-item-counter">
                                        <?= $iCounter ?>
                                    </div>
                                <?php } ?>
                                <div class="widget-item-text">
                                    <div class="widget-item-name">
                                        <?= $arItem['NAME'] ?>
                                    </div>
                                    <?php if (!empty($arItem['PREVIEW_TEXT']) && $arVisual['PREVIEW']['SHOW']) { ?>
                                        <div class="widget-item-description">
                                            <?= $arItem['PREVIEW_TEXT'] ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
                <?php if ($arVisual['HIDE']) { ?>
                    <div class="widget-button-wrap">
                        <div class="widget-button intec-ui intec-ui-control-button intec-ui-mod-round-half intec-ui-scheme-current intec-ui-size-5 intec-ui-mod-transparent" data-role="button"></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if ($arVisual['HIDE'] && $iCounter > $arVisual['LINE_COUNT']) include(__DIR__.'/parts/script.php') ?>