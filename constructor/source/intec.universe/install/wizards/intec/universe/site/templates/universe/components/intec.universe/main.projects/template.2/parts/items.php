<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var $arVisual
 * @var string $sTag
 */

?>
<?php $vItems = function (&$arItems) use (&$arVisual, &$sTag) { ?>
    <?php if (!empty($arItems)) { ?>
        <?= Html::beginTag('div', [
            'class' => [
                'widget-items',
                'intec-grid',
                'intec-grid-wrap'
            ],
            'data' => [
                'wide' => $arVisual['WIDE'] ? 'true' : 'false',
                'grid' => $arVisual['COLUMNS']
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
                    'class' => Html::cssClassFromArray([
                        'widget-item' => true,
                        'intec-grid-item' => [
                            $arVisual['COLUMNS'] => true,
                            '1200-4' => $arVisual['COLUMNS'] >= 5,
                            '1024-2' => true,
                            '768-1' => true
                        ]
                    ], true)
                ]) ?>
                    <?= Html::beginTag($sTag, [
                        'id' => $sAreaId,
                        'href' => $sTag === 'a' ? $arItem['DETAIL_PAGE_URL'] : null,
                        'class' => 'widget-item-wrapper'
                    ]) ?>
                        <?= Html::tag('div', '', [
                            'class' => 'widget-item-picture',
                            'data' => [
                                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                            ],
                            'style' => [
                                'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                            ]
                        ]) ?>
                        <div class="widget-item-fade"></div>
                        <div class="widget-item-name">
                            <?= $arItem['NAME'] ?>
                        </div>
                    <?= Html::endTag($sTag) ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php }; ?>