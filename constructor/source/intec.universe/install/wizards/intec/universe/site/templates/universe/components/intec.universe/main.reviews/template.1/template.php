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

$sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';

/**
 * @var Closure $vPicture()
 */
include(__DIR__.'/parts/picture.php');

?>
<div class="widget c-reviews c-reviews-template-1" id="<?= $sTemplateId ?>">
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
                    'class' => Html::cssClassFromArray([
                        'widget-items' => true,
                        'owl-carousel' => $arVisual['SLIDER']['USE'],
                        'intec-grid' => [
                            '' => !$arVisual['SLIDER']['USE'],
                            'wrap' => !$arVisual['SLIDER']['USE'],
                            'a-v-stretch' => !$arVisual['SLIDER']['USE'],
                            'i-h-15' => !$arVisual['SLIDER']['USE'],
                            'i-v-25' => !$arVisual['SLIDER']['USE']
                        ]
                    ], true),
                    'data' => [
                        'role' => 'container',
                        'grid' => $arVisual['COLUMNS'],
                        'slider' => $arVisual['SLIDER']['USE'] ? 'true' : 'false'
                    ]
                ]) ?>
                    <?php foreach ($arResult['ITEMS'] as $arItem) {

                        $sId = $sTemplateId.'_'.$arItem['ID'];
                        $sAreaId = $this->GetEditAreaId($sId);
                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                        $arData = $arItem['DATA'];
                        $sText = $arItem['PREVIEW_TEXT'];

                        if (empty($sText))
                            $sText = $arItem['DETAIL_TEXT'];

                        if (empty($sText))
                            continue;

                    ?>
                        <?= Html::beginTag('div', [
                            'class' => Html::cssClassFromArray([
                                'widget-item' => true,
                                'intec-grid-item' => [
                                    $arVisual['COLUMNS'] => !$arVisual['SLIDER']['USE'],
                                    '1024-1' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 2
                                ]
                            ], true)
                        ]) ?>
                            <div class="widget-item-wrapper intec-grid intec-grid-768-wrap" id="<?= $sAreaId ?>">
                                <div class="widget-item-picture-wrap intec-grid-item-auto intec-grid-item-768-1">
                                    <?php $vPicture($arItem) ?>
                                </div>
                                <div class="widget-item-text intec-grid-item intec-grid-item-768-1">
                                    <?= Html::tag($sTag, $arItem['NAME'], [
                                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                                        'class' => 'widget-item-name',
                                        'target' => $sTag === 'Y' && $arVisual['LINK']['BLANK'] ? '_blank' : null
                                    ]) ?>
                                    <?php if ($arVisual['POSITION']['SHOW'] && !empty($arData['POSITION'])) { ?>
                                        <div class="widget-item-position">
                                            <?= $arData['POSITION'] ?>
                                        </div>
                                    <?php } ?>
                                    <div class="widget-item-description">
                                        <?= $sText ?>
                                    </div>
                                </div>
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
<?php if ($arVisual['VIDEO']['SHOW'] || $arVisual['SLIDER']['SHOW'])
    include(__DIR__.'/parts/script.php');
?>