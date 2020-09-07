<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (empty($arResult['ITEMS']))
    return;

$arBlocks = $arResult['BLOCKS'];
$arVisual = $arResult['VISUAL'];

$iCounter = 0;

?>
<div class="widget c-videos c-videos-template-2" id="<?= $sTemplateId ?>">
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
                <div class="widget-content-wrapper intec-grid intec-grid-a-v-stretch intec-grid-768-wrap">
                    <div class="widget-viewport intec-grid-item intec-grid-item-768-1">
                        <div class="widget-viewport-wrapper">
                            <?php $arFirstItem = ArrayHelper::getFirstValue($arResult['ITEMS']); ?>
                            <?= Html::tag('iframe', '', [
                                'class' => 'widget-viewport-item',
                                'src' => $arFirstItem['URL']['embed'],
                                'allow' => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
                                'allowfullscreen' => '',
                                'data-role' => 'view'
                            ]) ?>
                            <?php unset($arFirstItem) ?>
                        </div>
                    </div>
                    <div class="widget-items-wrap intec-grid-item-auto intec-grid-item-768-1">
                        <div class="widget-items scroll-mod-hiding scrollbar-inner" data-role="items">
                            <?php foreach ($arResult['ITEMS'] as $arItem) {

                                $sId = $sTemplateId.'_'.$arItem['ID'];
                                $sAreaId = $this->GetEditAreaId($sId);
                                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                                $iCounter++;

                                if (defined('EDITOR') && $iCounter > 4)
                                    break;

                                $sPicture = $arItem['PICTURE'];

                                if ($sPicture['SOURCE'] === 'detail' || $sPicture['SOURCE'] === 'preview') {
                                    $sPicture = CFile::ResizeImageGet($sPicture, [
                                        'width' => 200,
                                        'height' => 200
                                    ]);

                                    if (!empty($sPicture))
                                        $sPicture = $sPicture['src'];
                                } else if ($sPicture['SOURCE'] === 'service') {
                                    $sPicture = $sPicture['SRC'];
                                } else {
                                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                                }

                            ?>
                                <?= Html::beginTag('div', [
                                    'id' => $sAreaId,
                                    'class' => [
                                        'widget-item',
                                        'intec-cl-text-hover'
                                    ],
                                    'data' => [
                                        'role' => 'item',
                                        'id' => $arItem['URL']['ID'],
                                        'active' => 'false'
                                    ]
                                ]) ?>
                                    <div class="widget-item-wrapper intec-grid intec-grid-a-v-center">
                                        <div class="intec-grid-item-auto">
                                            <?= Html::beginTag('div', [
                                                'class' => 'widget-item-picture',
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                                ],
                                                'style' => [
                                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                                ]
                                            ]) ?>
                                                <div class="widget-item-picture-decoration">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            <?= Html::endTag('div') ?>
                                        </div>
                                        <div class="intec-grid-item">
                                            <div class="widget-item-name">
                                                <?= $arItem['NAME'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?= Html::endTag('div') ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
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
<?php include(__DIR__.'/parts/script.php') ?>