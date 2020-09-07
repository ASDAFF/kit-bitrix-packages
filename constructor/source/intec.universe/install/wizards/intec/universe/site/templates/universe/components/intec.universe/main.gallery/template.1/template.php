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
        'c-gallery',
        'c-gallery-template-1'
    ],
    'data' => [
        'columns' => $arVisual['COLUMNS'],
        'delimiters' => $arVisual['DELIMITERS'] ? 'true' : 'false'
    ]
]) ?>
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
                <?php if (!empty($arResult['SECTIONS'])) { ?>
                    <ul class="<?= Html::cssClassFromArray([
                        'widget-tabs',
                        'intec-ui' => [
                            '',
                            'control-tabs',
                            'mod-block',
                            'mod-position-'.$arVisual['TABS']['POSITION'],
                            'scheme-current',
                            'view-1'
                        ]
                    ]) ?>">
                        <?php $iCounter = 0 ?>
                        <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
                            <li class="<?= Html::cssClassFromArray([
                                'intec-ui-part-tab' => true,
                                'active' => $iCounter === 0
                            ], true) ?>">
                                <a href="<?= '#'.$sTemplateId.'-tab-'.$iCounter ?>" role="tab" data-toggle="tab"><?= $arSection['NAME'] ?></a>
                            </li>
                            <?php $iCounter++ ?>
                        <?php } ?>
                    </ul>
                    <div class="widget-tabs-content intec-ui intec-ui-control-tabs-content">
                        <?php $iCounter = 0 ?>
                        <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
                            <?php $iCount = 0 ?>
                            <?= Html::beginTag('div', [
                                'id' => $sTemplateId.'-tab-'.$iCounter,
                                'class' => Html::cssClassFromArray([
                                    'intec-ui-part-tab' => true,
                                    'active' => $iCounter === 0
                                ], true),
                                'role' => 'tabpanel'
                            ]) ?>
                                <?= Html::beginTag('div', [
                                    'class' => Html::cssClassFromArray([
                                        'widget-items' => true,
                                        'intec-grid' => [
                                            '' => true,
                                            'wrap' => true,
                                            'a-v-start' => true,
                                            'a-h-'.$arVisual['ALIGNMENT'] => true,
                                            'i-4' => $arVisual['DELIMITERS']
                                        ]
                                    ], true),
                                    'data' => [
                                        'role' => 'items'
                                    ]
                                ]) ?>
                                    <?php foreach ($arSection['ITEMS'] as $arItem) {

                                        $sId = $sTemplateId.'_'.$arItem['ID'];
                                        $sAreaId = $this->GetEditAreaId($sId);
                                        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                                        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                                        $iCount++;
                                        $sPicture = $arItem['PREVIEW_PICTURE'];

                                        if (empty($sPicture))
                                            $sPicture = $arItem['DETAIL_PICTURE'];

                                        $sPictureResized = CFile::ResizeImageGet($sPicture, [
                                            'width' => 500,
                                            'height' => 500
                                        ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                        if (!empty($sPictureResized)) {
                                            $sPictureResized = $sPictureResized['src'];
                                        } else {
                                            $sPictureResized = $sPicture['SRC'];
                                        }

                                        $sPicture = $sPicture['SRC'];

                                        if ($arVisual['SECTIONS']['ELEMENTS']['COUNT'] > 0)
                                            if ($iCount > $arVisual['SECTIONS']['ELEMENTS']['COUNT'])
                                                break;

                                    ?>
                                        <?= Html::beginTag('div', [
                                            'class' => Html::cssClassFromArray([
                                                'widget-item' => true,
                                                'intec-grid-item' => [
                                                    $arVisual['COLUMNS'] => true,
                                                    '1000-4' => $arVisual['COLUMNS'] >= 5,
                                                    '800-3' => $arVisual['COLUMNS'] >= 4,
                                                    '600-2' => $arVisual['COLUMNS'] >= 3,
                                                    '400-1' => true
                                                ]
                                            ], true),
                                            'data' => [
                                                'role' => 'item',
                                                'src' => $sPicture,
                                                'preview-src' => $sPictureResized
                                            ]
                                        ]) ?>
                                            <?= Html::beginTag('div', [
                                                'id' => $sAreaId,
                                                'class' => 'widget-item-picture',
                                                'data' => [
                                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                                                ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPictureResized.'\')' : null
                                    ]
                                            ]) ?>
                                                <div class="widget-item-button">
                                                    <i class="far fa-search-plus"></i>
                                                </div>
                                                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPictureResized, [
                                                    'alt' => $arItem['NAME'],
                                                    'title' => $arItem['NAME'],
                                                    'loading' => 'lazy',
                                                    'data' => [
                                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                                                    ]
                                                ]) ?>
                                            <?= Html::endTag('div') ?>
                                        <?= Html::endTag('div') ?>
                                    <?php } ?>
                                <?= Html::endTag('div') ?>
                            <?= Html::endTag('div') ?>
                            <?php $iCounter++ ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if ($arBlocks['FOOTER']['SHOW']) { ?>
                <div class="widget-footer align-<?= $arBlocks['FOOTER']['POSITION'] ?>">
                    <?php if ($arBlocks['FOOTER']['BUTTON']['SHOW']) { ?>
                        <a href="<?= $arBlocks['FOOTER']['BUTTON']['LINK'] ?>" class="<?= Html::cssClassFromArray([
                            'widget-footer-button',
                            'intec-ui' => [
                                '',
                                'control-button',
                                'mod-transparent',
                                'mod-round-half',
                                'scheme-current',
                                'size-5'
                            ]
                        ]) ?>"><?= Html::encode($arBlocks['FOOTER']['BUTTON']['TEXT']) ?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if (!defined('EDITOR')) { ?>
        <?php include(__DIR__.'/parts/script.php') ?>
    <?php } ?>
<?= Html::endTag('div') ?>