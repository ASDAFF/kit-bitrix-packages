<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
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
$bItemsFirst = true;

/**
 * @var Closure $hText($arData, $bHeaderH1, $arForm)
 * @var Closure $vImage($arData)
 * @var Closure $vAdditional()
 */
$vText = include(__DIR__.'/parts/text.php');
$vPicture = include(__DIR__.'/parts/picture.php');
$vVideo = include(__DIR__.'/parts/video.php');
$vAdditional = include(__DIR__.'/parts/additional.php');

?>
<div class="widget c-slider c-slider-template-1" id="<?= $sTemplateId ?>">
    <div class="intec-content-wrap">
        <?= Html::beginTag('div', [
            'class' => 'widget-content',
            'data' => [
                'role' => 'content',
                'scheme' => 'white'
            ],
            'data-nav-view' => $bSliderUse && $arVisual['SLIDER']['NAV']['SHOW'] ? $arVisual['SLIDER']['NAV']['VIEW'] : null,
            'data-dots-view' => $bSliderUse && $arVisual['SLIDER']['DOTS']['SHOW'] ? $arVisual['SLIDER']['DOTS']['VIEW'] : null
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

                    $sItemName = $arItem['NAME'];

                    $sTag = !empty($arData['LINK']['VALUE']) && !$arData['BUTTON']['SHOW'] ? 'a' : 'div';
                    $sPicture = ArrayHelper::getValue($arItem, ['PREVIEW_PICTURE', 'SRC']);

                    if (empty($sPicture))
                        $sPicture = ArrayHelper::getValue($arItem, ['DETAIL_PICTURE', 'SRC']);

                    if (empty($sPicture))
                        $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                ?>
                    <?= Html::beginTag($sTag, [
                        'href' => $sTag === 'a' ? $arData['LINK']['VALUE'] : null,
                        'class' => 'widget-item',
                        'target' => $sTag === 'a' && $arData['LINK']['BLANK'] ? '_blank' : null,
                        'data' => [
                            'item-scheme' => $arData['SCHEME'],
                            'text-position' => $arData['TEXT']['POSITION'],
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
                        <div class="intec-content intec-content-visible intec-content-primary">
                            <div class="intec-content-wrapper">
                                <div class="widget-item-content" id="<?= $sAreaId ?>">
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'widget-item-content-body' => true,
                                            'intec-grid' => [
                                                '' => true,
                                                'i-h-20' => true,
                                                'a-h-center' => $arData['TEXT']['POSITION'] === 'center' && $arData['TEXT']['HALF']
                                            ]
                                        ], true),
                                        'style' => [
                                            'height' => $arVisual['HEIGHT'].'px'
                                        ]
                                    ]) ?>
                                        <?php if ($arData['TEXT']['POSITION'] === 'right') {
                                            $vPicture($arData);
                                        } ?>
                                        <?php $vText($arData, $bItemsFirst && $arVisual['HEADER']['H1'], $arResult['FORM']) ?>
                                        <?php if ($arData['TEXT']['POSITION'] === 'left') {
                                            $vPicture($arData);
                                        } ?>
                                    <?= Html::endTag('div') ?>
                                    <?php $vAdditional($arData) ?>
                                </div>
                            </div>
                        </div>
                    <?= Html::endTag($sTag) ?>
                    <?php $bItemsFirst = false ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
            <?php include(__DIR__.'/parts/special.buttons.php') ?>
            <?php include(__DIR__.'/parts/navigation.php') ?>
        <?= Html::endTag('div') ?>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>
