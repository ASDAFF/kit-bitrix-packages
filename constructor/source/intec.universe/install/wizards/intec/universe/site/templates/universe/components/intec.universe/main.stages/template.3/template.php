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

$bHide = count($arResult['ITEMS']) > 3;

$iCounter = 0;

?>
<div class="widget c-stages c-stages-template-3" id="<?= $sTemplateId ?>">
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
            <?= Html::beginTag('div', [
                'class' => [
                    'widget-content'
                ]
            ]) ?>
                <?= Html::beginTag('div', [
                    'class' => 'widget-items',
                    'intec-grid' => [
                        '',
                        'wrap',
                        'a-v-stretch',
                        'a-h-center',
                        'i-h-20'
                    ],
                    'data' => [
                        'role' => 'items',
                        'hide-items' => $bHide ? 'true' : 'false',
                        'state' => $bHide ? 'collapsed' : null
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arData = $arItem['DATA'];

                    $iCounter++;

                    ?>
                        <?= Html::beginTag('div', [
                            'id' => $sAreaId,
                            'class' => 'widget-item',
                            'data' => [
                                'role' => 'item',
                                'action' => $bHide && $iCounter > 3 ? 'hide': 'show'
                            ]
                        ]) ?>
                            <div class="widget-item-wrap intec-grid intec-grid-1024-wrap">
                                <div class="widget-item-name intec-grid-item-auto intec-grid-item-1024-1">
                                    <div class="intec-grid">
                                        <div class="intec-grid-item-auto">
                                            <div class="widget-item-name-count">
                                                <?= $iCounter ?>
                                            </div>
                                        </div>
                                        <div class="intec-grid-item">
                                            <div class="widget-item-name-text" data-size="<?= $arVisual['NAME']['SIZE'] ?>">
                                                <?= Html::decode($arItem['NAME']) ?>
                                            </div>
                                            <?php if (!empty($arData['TIME']['VALUE'])) { ?>
                                                <div class="widget-item-name-time">
                                                    <div class="intec-grid">
                                                        <div class="intec-grid-item-auto">
                                                            <div class="widget-item-name-time-icon"></div>
                                                        </div>
                                                        <div class="intec-grid-item">
                                                            <div class="widget-item-name-time-text">
                                                                <?= Loc::getMessage('C_MAIN_STAGES_TEMPLATE_3_TIME_TEXT') ?>
                                                            </div>
                                                            <div class="widget-item-name-time-value">
                                                                <?= $arData['TIME']['VALUE'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-item-description intec-grid-item intec-grid-item-1024-1">
                                    <?= $arItem[$arVisual['TEXT']['SOURCE']] ?>
                                </div>
                            </div>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                <?= Html::endTag('div') ?>
            <?= Html::endTag('div') ?>
            <?php if ($bHide && $iCounter > 3) { ?>
                <div class="widget-button-wrap">
                    <div class="widget-button intec-ui intec-ui-control-button intec-ui-mod-round-half intec-ui-scheme-current intec-ui-size-5 intec-ui-mod-transparent" data-role="button"></div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>
<?php if ($bHide && $iCounter > 3) include(__DIR__ . '/parts/script.php') ?>