<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $item
 */

$sPicture = null;

if (!empty($item['PREVIEW_PICTURE']['SRC']))
    $sPicture = $item['PREVIEW_PICTURE']['SRC'];
else if (!empty($item['PREVIEW_PICTURE_SECOND']['SRC']))
    $sPicture = $item['PREVIEW_PICTURE_SECOND']['SRC'];

if (empty($sPicture))
    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

if (!empty($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']))
    $sPictureTitle = $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'];
else
    $sPictureTitle = $item['NAME'];

$sPercent = ArrayHelper::getFirstValue($item['ITEM_PRICES']);

if (!empty($sPercent['PERCENT']))
    $sPercent = $sPercent['PERCENT'];

?>
<div class="catalog-item-picture-container">
    <?= Html::tag('a', null, [
        'class' => [
            'catalog-item-picture',
            'intec-image-effect'
        ],
        'href' => $item['DETAIL_PAGE_URL'],
        'title' => $sPictureTitle,
        'style' => [
            'background-image' => 'url(\''.$sPicture.'\')'
        ]
    ]) ?>
    <?php if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' || $item['LABEL']) { ?>
        <div class="catalog-item-sticker">
            <?php if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($sPercent)) { ?>
                <div class="catalog-item-sticker-item catalog-item-sticker-percent">
                    <span>
                        <?= '-'.$sPercent.'%' ?>
                    </span>
                </div>
            <?php } ?>
            <?php if ($item['LABEL']) { ?>
                <div class="catalog-item-sticker-item catalog-item-sticker-label">
                    <?php if (!empty($item['LABEL_ARRAY_VALUE'])) { ?>
                        <?php foreach ($item['LABEL_ARRAY_VALUE'] as $value) { ?>
                            <div class="catalog-item-sticker-label-item">
                                <span>
                                    <?= $value ?>
                                </span>
                            </div>
                        <?php } ?>
                        <?php unset($value) ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<?php unset($sPicture, $sPictureTitle, $sPercent) ?>