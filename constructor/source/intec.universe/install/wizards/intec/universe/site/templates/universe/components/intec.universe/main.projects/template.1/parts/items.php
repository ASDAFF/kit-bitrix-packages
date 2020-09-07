<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arBlocks
 * @var array $arVisual
 */

if (empty($arItems))
    return;

?>
<?= Html::beginTag('div', [
    'class' => Html::cssClassFromArray([
        'widget-items' => true,
        'intec-grid' => [
            '' => !$arVisual['SLIDER']['USE'],
            'wrap' => !$arVisual['SLIDER']['USE'],
            'i-15' => !$arVisual['SLIDER']['USE'],
            'a-v-start' => !$arVisual['SLIDER']['USE'],
            'a-h-'.$arVisual['ALIGNMENT'] => !$arVisual['SLIDER']['USE']
        ],
        'owl-carousel' => $arVisual['SLIDER']['USE']
    ], true),
    'data' => [
        'role' => $arVisual['SLIDER']['USE'] ? 'slider' : null
    ]
]) ?>
    <?php foreach ($arItems as $arItem) {

        $sId = $sTemplateId.'_'.$arItem['ID'];
        $sAreaId = $this->GetEditAreaId($sId);
        $this->AddEditAction($sId, $arItem['EDIT_LINK']);
        $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

        $sTag = $arVisual['LINK']['USE'] ? 'a' : 'div';
        $sPicture = $arItem['PREVIEW_PICTURE'];

        if (empty($sPicture))
            $sPicture = $arItem['DETAIL_PICTURE'];

        if (!empty($sPicture)) {
            $sPicture = CFile::ResizeImageGet($sPicture, [
                'width' => 700,
                'height' => 700
            ], BX_RESIZE_IMAGE_PROPORTIONAL);

            if (!empty($sPicture))
                $sPicture = $sPicture['src'];
        }

        if (empty($sPicture))
            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

    ?>
        <?= Html::beginTag('div', [
            'id' => $sAreaId,
            'class' => Html::cssClassFromArray([
                'widget-item' => true,
                'intec-grid-item' => [
                    $arVisual['COLUMNS'] => !$arVisual['SLIDER']['USE'],
                    '1100-3' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 4,
                    '900-2' => !$arVisual['SLIDER']['USE'] && $arVisual['COLUMNS'] >= 3,
                    '600-1' => !$arVisual['SLIDER']['USE']
                ]
            ], true)
        ]) ?>
            <?= Html::beginTag($sTag, [
                'class' => 'widget-item-wrapper',
                'href' => $arVisual['LINK']['USE'] ? $arItem['DETAIL_PAGE_URL'] : null,
                'target' => $arVisual['LINK']['USE'] && $arVisual['LINK']['BLANK'] ? '_blank' : null,
                'data' => [
                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                ],
                'style' => [
                    'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                ]
            ]) ?>
                <div class="widget-item-name">
                    <div class="widget-item-name-wrapper">
                        <?= $arItem['NAME'] ?>
                    </div>
                </div>
            <?= Html::endTag($sTag) ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?= Html::endTag('div') ?>