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
 * @var Closure $vBlocks($arBlocks, $position, $half)
 */
$vText = include(__DIR__.'/parts/text.php');
$vPicture = include(__DIR__.'/parts/picture.php');
$vNavigation = include(__DIR__.'/parts/navigation.php');

if ($arVisual['VIDEO']['SHOW'])
    $vVideo = include(__DIR__.'/parts/video.php');


if ($arResult['BLOCKS']['USE'])
    $vBlocks = include(__DIR__.'/parts/blocks.php');

?>
<div class="widget c-slider c-slider-template-2" id="<?= $sTemplateId ?>">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="widget-content">
                <?= Html::beginTag('div', [
                    'class' => 'widget-slider',
                    'data' => [
                        'role' => 'content',
                        'indent-left' => !empty($arResult['BLOCKS']['LEFT']) ? 'true' : 'false',
                        'indent-right' => !empty($arResult['BLOCKS']['RIGHT']) ? 'true' : 'false',
                        'rounded' => $arVisual['ROUNDED'] ? 'true' : 'false',
                        'nav-view' => $bSliderUse && $arVisual['SLIDER']['NAV']['SHOW'] ? $arVisual['SLIDER']['NAV']['VIEW'] : 'none',
                        'dots-view' =>  $bSliderUse && $arVisual['SLIDER']['DOTS']['SHOW'] ? $arVisual['SLIDER']['DOTS']['VIEW'] : 'none',
                        'scheme' => 'dark'
                    ]
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
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'widget-item-wrapper',
                                        'intec-grid'
                                    ],
                                    'style' => [
                                        'height' => $arVisual['HEIGHT'].'px'
                                    ]
                                ]) ?>
                                    <?php $vText($arData) ?>
                                    <?php if ($arVisual['PICTURE']['SHOW'] && !empty($arData['PICTURE']['VALUE'])) {
                                        $vPicture($arData);
                                    } ?>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag($sTag) ?>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                    <?php $vNavigation() ?>
                <?= Html::endTag('div') ?>
                <?php if ($arResult['BLOCKS']['USE']) { ?>
                    <?php $vBlocks($arResult['BLOCKS']['LEFT'], 'left', $arVisual['BLOCKS']['HALF']) ?>
                    <?php $vBlocks($arResult['BLOCKS']['RIGHT'], 'right', $arVisual['BLOCKS']['HALF']) ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>