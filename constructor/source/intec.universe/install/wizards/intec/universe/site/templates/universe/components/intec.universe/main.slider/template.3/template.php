<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

$bSliderUse = count($arResult['ITEMS']) > 1;
$bDesktop = Core::$app->browser->isDesktop;

/**
 * @var Closure $vText($arData)
 * @var Closure $vPicture($arData)
 * @var Closure $vNavigation()
 * @var Closure $vVideo($arData)
 * @var Closure $vBlocks($arBlocks)
 */
$vText = include(__DIR__.'/parts/text.php');
$vVideo = include(__DIR__.'/parts/video.php');
$vNavigation = include(__DIR__.'/parts/navigation.php');

if ($arVisual['PICTURE']['SHOW'])
    $vPicture = include(__DIR__.'/parts/picture.php');

if ($arVisual['BLOCKS']['USE'] && !empty($arResult['BLOCKS']))
    $vBlocks = include(__DIR__.'/parts/blocks.php');

?>
<div class="widget c-slider c-slider-template-3" id="<?= $sTemplateId ?>">
    <?php if (!$arVisual['WIDE']) { ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
    <?php } ?>
    <?= Html::beginTag('div', [
        'class' => 'widget-content',
        'data' => [
            'role' => 'content',
            'wide' => $arVisual['WIDE'] ? 'true' : 'false',
            'blocks-use' => $arVisual['BLOCKS']['USE'] && !empty($arResult['BLOCKS']) ? 'true' : 'false',
            'scheme' => 'white'
        ],
        'data-blocks-position' => $arVisual['BLOCKS']['USE'] ? $arVisual['BLOCKS']['POSITION'] : null,
        'data-nav-view' => $bSliderUse && $arVisual['SLIDER']['NAV']['SHOW'] ? $arVisual['SLIDER']['NAV']['VIEW'] : null,
        'data-dots-view' => $bSliderUse && $arVisual['SLIDER']['DOTS']['SHOW'] ? $arVisual['SLIDER']['DOTS']['VIEW'] : null
    ]) ?>
        <?= Html::beginTag('div', [
            'class' => 'widget-slider'
        ]) ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'widget-items' => true,
                    'owl-carousel' => $bSliderUse
                ], true),
                'data' => [
                    'role' => 'container'
                ]
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $arData = $arItem['DATA'];

                    $sTag = !empty($arData['LINK']['VALUE']) && !$arData['BUTTON']['SHOW'] ? 'a' : 'div';
                    $sPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);

                    if (empty($sPicture))
                        $sPicture = ArrayHelper::getValue($arItem, ['DETAIL_PICTURE', 'SRC']);

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag($sTag, [
                        'id' => $sAreaId,
                        'href' => $sTag === 'a' ? $arData['LINK']['VALUE'] : null,
                        'class' => 'widget-item',
                        'target' => $sTag === 'a' && $arData['LINK']['BLANK'] ? '_blank' : null,
                        'data' => [
                            'item-scheme' => $arData['SCHEME'],
                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                            'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                        ],
                        'style' => [
                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                        ]
                    ]) ?>
                        <?php if ($bDesktop && $arVisual['VIDEO']['SHOW']) {
                            $vVideo($arData, $sPicture);
                        } ?>
                        <?php if ($arData['FADE']) { ?>
                            <div class="widget-item-fade"></div>
                        <?php } ?>
                        <?php if (!$arVisual['WIDE'] || !$arVisual['BLOCKS']['USE'] || $arVisual['BLOCKS']['POSITION'] !== 'right') { ?>
                            <div class="intec-content intec-content-primary intec-content-visible">
                                <div class="intec-content-wrapper">
                        <?php } ?>
                                <div class="widget-item-content">
                                    <?= Html::beginTag('div', [
                                        'class' => [
                                            'widget-item-content-body',
                                            'intec-grid',
                                            'intec-grid-i-h-12'
                                        ],
                                        'style' => [
                                            'height' => $arVisual['HEIGHT'].'px'
                                        ]
                                    ]) ?>
                                        <?php $vText($arData) ?>
                                        <?php if ($arData['PICTURE']['SHOW']) {
                                            $vPicture($arData);
                                        } ?>
                                    <?= Html::endTag('div') ?>
                                </div>
                        <?php if (!$arVisual['WIDE'] || !$arVisual['BLOCKS']['USE'] || $arVisual['BLOCKS']['POSITION'] !== 'right') { ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?= Html::endTag($sTag) ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
            <?php $vNavigation() ?>
        <?= Html::endTag('div') ?>
        <?php if ($arVisual['BLOCKS']['USE'] && !empty($arResult['BLOCKS'])) {
            $vBlocks($arResult['BLOCKS']);
        } ?>
    <?= Html::endTag('div') ?>
    <?php if (!$arVisual['WIDE']) { ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php include(__DIR__.'/parts/script.php') ?>