<?php use intec\core\helpers\Html;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arVisual
 * @var string $sPicture
 * @var CBitrixComponent $component
 */

?>
<?php return function (&$arItem) use (&$arVisual, $component) {

    /**
     * @var CAllMain $APPLICATION
     */
    global $APPLICATION;

?>
    <?php if ($arVisual['IMAGES']['SHOW'] && !empty($arItem['DATA']['IMAGES'])) { ?>
        <div class="widget-item-picture">
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:main.advantages',
                'template.27', [
                    'IBLOCK_ID' => $arItem['DATA']['IMAGES']['IBLOCK'],
                    'FILTER' => [
                        'ID' => $arItem['DATA']['IMAGES']['ID']
                    ],
                    'HEADER_SHOW' => 'N',
                    'DESCRIPTION_SHOW' => 'N',
                    'THEME' => $arItem['DATA']['THEME'],
                    'SORT_BY' => 'SORT',
                    'ORDER_BY' => 'ASC',
                    'IN_BLOCK' => 'Y'
                ],
                $component
            ) ?>
        </div>
    <?php } else {

        $sPicture = $arItem['PREVIEW_PICTURE'];

        if (empty($sPicture))
            $sPicture = $arItem['DETAIL_PICTURE'];

        if (!empty($sPicture))
            $sPicture = $sPicture['SRC'];

    ?>
        <?php if (!empty($sPicture)) { ?>
            <div class="widget-item-picture">
                <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                    'class' => 'widget-element-picture',
                    'loading' => 'lazy',
                    'alt' => '',
                    'title' => '',
                    'data' => [
                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                    ]
                ]) ?>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>