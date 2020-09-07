<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php if ($arVisual['ADDITIONAL_TEXT']['SHOW'] && !empty($arResult['DATA']['ADDITIONAL_TEXT']['VALUE'])) {

    $arData = $arResult['DATA']['ADDITIONAL_TEXT'];

?>
    <div class="catalog-element-additional-text">
        <?php if (!$arVisual['ADDITIONAL_TEXT']['WIDE']) { ?>
            <div class="intec-content">
                <div class="intec-content-wrapper">
        <?php } ?>
        <?= Html::beginTag('div', [
            'class' => 'catalog-element-additional-text-body',
            'data' => [
                'dark' => $arVisual['ADDITIONAL_TEXT']['DARK'] ? 'true' : 'false'
            ]
        ]) ?>
            <div class="intec-content">
                <div class="catalog-element-additional-text-body-wrapper">
                    <div class="intec-grid intec-grid-a-v-center">
                        <?php if (!empty($arData['PICTURE'])) {

                            $arPicture = CFile::ResizeImageGet($arData['PICTURE'], [
                                'width' => 600,
                                'height' => 600
                            ], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                        ?>
                            <div class="catalog-element-additional-text-picture intec-grid-item-3">
                                <div class="catalog-element-additional-text-picture-wrapper">
                                    <?= Html::img($arPicture['src'], [
                                        'alt' => $arResult['NAME'],
                                        'title' => $arResult['NAME'],
                                        'loading' => 'lazy'
                                    ]) ?>
                                </div>
                            </div>
                            <?php unset($arPicture) ?>
                        <?php } ?>
                        <div class="intec-grid-item">
                            <div class="catalog-element-additional-text-value">
                                <?= Html::stripTags($arData['VALUE'], ['br']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?= Html::endTag('div') ?>
        <?php if (!$arVisual['ADDITIONAL_TEXT']['WIDE']) { ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>
