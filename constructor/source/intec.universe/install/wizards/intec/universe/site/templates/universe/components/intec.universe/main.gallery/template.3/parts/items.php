<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<?php $vItems = function (&$arItems) use ($arVisual, $sTemplateId) { ?>
    <?php if (!empty($arItems)) { ?>
        <?= Html::beginTag('div', [
            'class' => Html::cssClassFromArray([
                'owl-carousel' => $arVisual['SLIDER']['USE'],
                'widget-items' => true,
                'intec-grid' => [
                    '' => true,
                    'wrap' => true
                ]
            ], true),
            'data' => [
                'role' => 'items'
            ]
        ]) ?>
            <?php foreach ($arItems as $arItem) {

                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

                $sPicture = $arItem['PREVIEW_PICTURE'];

                if (empty($sPicture))
                    $sPicture = $arItem['DETAIL_PICTURE'];

                $sPictureResized = CFile::ResizeImageGet($sPicture, [
                    'width' => 700,
                    'height' => 700
                ], BX_RESIZE_IMAGE_EXACT);

                if (!empty($sPictureResized)) {
                    $sPictureResized = $sPictureResized['src'];
                } else {
                    $sPictureResized = $sPicture['SRC'];
                }

                $sPicture = $sPicture['SRC'];

            ?>
                <?= Html::beginTag('div', [
                    'class' => Html::cssClassFromArray([
                        'widget-item' => true,
                        'intec-grid-item' => [
                            $arVisual['COLUMNS'] => true,
                            '1200-4' => $arVisual['COLUMNS'] >= 5,
                            '768-3' => $arVisual['COLUMNS'] >= 4,
                            '600-2' => $arVisual['COLUMNS'] >= 3,
                            '450-1' => true
                        ]
                    ], true),
                    'data' => [
                        'role' => 'item',
                        'src' => $sPicture,
                        'preview-src' => $sPictureResized
                    ]
                ]) ?>
                    <div class="widget-item-wrapper" id="<?= $sAreaId ?>">
                        <?= Html::beginTag('div', [
                            'class' => 'widget-item-picture',
                            'title' => Html::decode(Html::stripTags($arItem['NAME'])),
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPictureResized : null
                            ],
                            'style' => [
                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPictureResized.'\')' : null
                            ]
                        ]) ?>
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
                    </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php } ?>