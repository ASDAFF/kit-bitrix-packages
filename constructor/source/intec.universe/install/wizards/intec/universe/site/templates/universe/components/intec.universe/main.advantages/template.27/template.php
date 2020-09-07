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

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-advantages',
        'c-advantages-template-27'
    ],
    'data' => [
        'theme' => $arVisual['THEME'],
        'in-block' => $arVisual['IN_BLOCK'] ? 'true' : 'false'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
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
                <div class="widget-items" data-role="tabs">
                    <div class="widget-tabs">
                        <?php $iCounter = 0 ?>
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sPicture = $arItem['PREVIEW_PICTURE'];

                            if (empty($sPicture))
                                $sPicture = $arItem['DETAIL_PICTURE'];

                            if (empty($sPicture))
                                continue;

                        ?>
                            <?= Html::tag('div', $arItem['NAME'], [
                                'class' => Html::cssClassFromArray([
                                    'widget-tabs-item' => true,
                                    'intec-cl-border-hover' => $arVisual['THEME'] !== 'black' && $iCounter > 0,
                                    'intec-cl-border' => $arVisual['THEME'] !== 'black' && $iCounter < 1
                                ], true),
                                'data' => [
                                    'role' => 'tabs.item',
                                    'id' => $arItem['ID'],
                                    'active' => $iCounter < 1 ? 'true' : 'false'
                                ]
                            ]) ?>
                            <?php $iCounter++ ?>
                        <?php } ?>
                    </div>
                    <div class="widget-tabs-content">
                        <?php $iCounter = 0 ?>
                        <?php foreach ($arResult['ITEMS'] as $arItem) {

                            $sId = $sTemplateId.'_'.$arItem['ID'];
                            $sAreaId = $this->GetEditAreaId($sId);
                            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                            $sPicture = $arItem['PREVIEW_PICTURE'];

                            if (empty($sPicture))
                                $sPicture = $arItem['DETAIL_PICTURE'];

                            if (!empty($sPicture))
                                $sPicture = $sPicture['SRC'];
                            else
                                continue;

                        ?>
                            <?= Html::beginTag('div', [
                                'id' => $sAreaId,
                                'class' => 'widget-tabs-content-item',
                                'data' => [
                                    'role' => 'tabs.content.item',
                                    'id' => $arItem['ID'],
                                    'active' => $iCounter < 1 ? 'true' : 'false'
                                ],
                                'style' => [
                                    'display' => $iCounter < 1 ? 'block' : 'none'
                                ]
                            ]) ?>
                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                    'alt' => '',
                                    'title' => '',
                                    'loading' => 'lazy',
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ]
                                ]) ?>
                            <?= Html::endTag('div') ?>
                            <?php $iCounter++ ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php include(__DIR__.'/parts/script.php');