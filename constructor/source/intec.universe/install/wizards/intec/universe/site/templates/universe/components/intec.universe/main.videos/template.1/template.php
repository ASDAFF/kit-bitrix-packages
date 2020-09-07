<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (empty($arResult['ITEMS']))
    return;

$arHeader = $arResult['BLOCKS']['HEADER'];
$arDescription = $arResult['BLOCKS']['DESCRIPTION'];
$arVisual = $arResult['VISUAL'];
$arContent = $arResult['BLOCKS']['CONTENT'];
$arFooter = $arResult['BLOCKS']['FOOTER'];

?>
<div class="widget c-videos c-videos-template-1" id="<?= $sTemplateId ?>">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($arHeader['SHOW'] || $arDescription['SHOW']) { ?>
                <div class="widget-header">
                    <?php if ($arHeader['SHOW']) { ?>
                        <div class="widget-title align-<?= $arHeader['POSITION'] ?>">
                            <?= Html::encode($arHeader['TEXT']) ?>
                        </div>
                    <?php } ?>
                    <?php if ($arDescription['SHOW']) { ?>
                        <div class="widget-description align-<?= $arDescription['POSITION'] ?>">
                            <?= Html::encode($arDescription['TEXT']) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'widget-content' => true,
                    'owl-carousel' => $arVisual['SLIDER']['USE'],
                    'intec-grid' => [
                        '' => !$arVisual['SLIDER']['USE'],
                        'wrap' => !$arVisual['SLIDER']['USE'],
                        'a-v-start' => !$arVisual['SLIDER']['USE'],
                        'a-h-start' => !$arVisual['SLIDER']['USE'] && $arContent['POSITION'] === 'left',
                        'a-h-center' => !$arVisual['SLIDER']['USE'] && $arContent['POSITION'] === 'center',
                        'a-h-end' => !$arVisual['SLIDER']['USE'] && $arContent['POSITION'] === 'right',
                    ]
                ], true),
                'data' => [
                    'entity' => 'gallery',
                    'role' => $arVisual['SLIDER']['USE'] ? 'slider' : null
                ]
            ]) ?>
                <?php foreach ($arResult['ITEMS'] as $arItem) {

                    $sId = $sTemplateId.'_'.$arItem['ID'];
                    $sAreaId = $this->GetEditAreaId($sId);
                    $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                    $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                    $sPicture = $arItem['PICTURE'];

                    if ($sPicture['SOURCE'] === 'detail' || $sPicture['SOURCE'] === 'preview') {
                        $sPicture = CFile::ResizeImageGet($sPicture, [
                            'width' => 800,
                            'height' => 600
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
                        'class' => Html::cssClassFromArray([
                            'widget-item' => true,
                            'intec-grid-item' => [
                                $arVisual['COLUMNS'] => true,
                                '1050-4' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 5,
                                '900-3' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 4,
                                '750-2' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 3,
                                '550-1' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 2
                            ]
                        ], true),
                        'data-src' => !empty($arItem['URL']) ? $arItem['URL']['embed'] : null,
                        'data-play' => !empty($arItem['URL']) ? 'true' : 'false'
                    ]) ?>
                        <div class="widget-item-wrapper">
                            <?= Html::beginTag('div', [
                                'class' => 'widget-item-wrapper-2',
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ],
                                'style' => [
                                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                ]
                            ]) ?>
                                <div class="widget-item-fade"></div>
                                <div class="widget-item-decoration">
                                    <div class="widget-item-decoration-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="widget-item-decoration-icon">
                                            <path d="M216 354.9V157.1c0-10.7 13-16.1 20.5-8.5l98.3 98.9c4.7 4.7 4.7 12.2 0 16.9l-98.3 98.9c-7.5 7.7-20.5 2.3-20.5-8.4zM256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm0 48C145.5 56 56 145.5 56 256s89.5 200 200 200 200-89.5 200-200S366.5 56 256 56z"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?= Html::endTag('div') ?>
                        </div>
                    <?= Html::endTag('div') ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
            <?php if ($arFooter['SHOW']) { ?>
                <div class="widget-footer align-<?= $arFooter['POSITION'] ?>">
                    <?php if ($arFooter['BUTTON']['SHOW']) { ?>
                        <a class="widget-footer-button intec-cl-border intec-cl-background-hover" href="<?= $arFooter['BUTTON']['LINK'] ?>">
                            <?= $arFooter['BUTTON']['TEXT'] ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php include(__DIR__.'/parts/script.php') ?>