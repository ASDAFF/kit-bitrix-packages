<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 * @var string $sTemplateId
 * @var string $sType
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$sTitle = ArrayHelper::getValue($arParams, 'TITLE');
$bDisplayTitle = ArrayHelper::getValue($arParams, 'DISPLAY_TITLE') == 'Y' && !empty($sTitle);
$bTitleCenter = ArrayHelper::getValue($arParams, 'TITLE_ALIGN') == 'Y';

$sDescription = ArrayHelper::getValue($arParams, 'DESCRIPTION');
$bDisplayDescription = ArrayHelper::getValue($arParams, 'SHOW_DESCRIPTION') == 'Y' && !empty($sDescription);
$bDescriptionCenter = ArrayHelper::getValue($arParams, 'DESCRIPTION_ALIGN') == 'Y';

?>
<div class="widget-brands" id="<?= $sTemplateId ?>">
    <?php if ($bDisplayTitle) { ?>
        <div class="title <?= $bTitleCenter ? 'text-center' : null ?>"><?= $sTitle ?></div>
    <?php } ?>
    <?php if ($bDisplayDescription) { ?>
        <div class="description <?= $bDescriptionCenter ? 'text-center' : null ?>"><?= $sDescription ?></div>
    <?php } ?>
    <div class="widget-brands-wrapper">
        <div class="widget-brands-navigation">
            <div class="intec-aligner"></div>
            <div class="widget-brands-navigation-wrapper">
                <div class="widget-brands-navigation-previous" data-move="previous">
                    <i class="fa fa-arrow-left intec-cl-text-hover"></i>
                </div>
                <div class="widget-brands-navigation-next" data-move="next">
                    <i class="fa fa-arrow-right intec-cl-text-hover"></i>
                </div>
            </div>
        </div>
        <div class="widget-brands-slider owl-carousel owl-centered">
            <?php foreach ($arResult['ITEMS'] as $arItem) {
                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);
                $sImage = null;

                if (!empty($arItem['PREVIEW_PICTURE'])) {
                    $sImage = $arItem['PREVIEW_PICTURE'];
                } else if (!empty($arItem['DETAIL_PICTURE'])) {
                    $sImage = $arItem['DETAIL_PICTURE'];
                }

                $sImage = CFile::ResizeImageGet($sImage, array(
                    'width' => 450,
                    'height' => 300
                ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                if (!empty($sImage)) {
                    $sImage = $sImage['src'];
                } else {
                    $sImage = null;
                }
                ?>
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="widget-brands-item" id="<?= $sAreaId ?>">
                    <img loading="lazy" alt="<?= $arItem['NAME'] ?>" src="<?= $sImage ?>">
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="widget-news-dots"></div>
</div>
<script type="text/javascript">
    (function ($, api) {
        $(document).ready(function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId.'.widget-brands') ?>);
            root.find('.owl-carousel').each(function () {
                var slider = $(this);
                var parent = slider.parents(':eq(1)');
                var navigation = parent.find('.widget-brands-navigation');
                var dots = parent.find('.widget-brands-dots');
                var refresh = function (event) {
                    if (event.page.size < event.item.count) {
                        navigation.show();
                    } else {
                        navigation.hide();
                    }
                };

                slider.on('initialized.owl.carousel', refresh)
                    .on('resized.owl.carousel', refresh)
                    .on('refreshed.owl.carousel', refresh);

                slider.owlCarousel({
                    'center': false,
                    'loop': false,
                    'stagePadding': 5,
                    'nav': false,
                    'dots': true,
                    'dotsData': false,
                    'autoplay': <?= $arParams['AUTOPLAY'] == 'Y' ? 'true' : 'false' ?>,
                    <?php if ($arParams['AUTOPLAY'] == 'Y' && $arParams['TIMEOUT_AUTOPLAY']) { ?>
                        'autoplayTimeout': <?= $arParams['TIMEOUT_AUTOPLAY'] ?>,
                    <?php } ?>
                    'autoplayHoverPause': true,
                    'dotsContainer': dots,
                    'responsive': {
                        0: {'items': 1},
                        640: {'items': 2},
                        960: {'items': <?= $arParams['COUNT_ELEMENT_IN_ROW'] ?>}
                    }
                });

                navigation.find('[data-move]').on('click', function (event) {
                    var self = $(this);
                    var value = self.data('move');

                    if (value == 'next') {
                        slider.trigger('next.owl.carousel');
                    } else if (value == 'previous') {
                        slider.trigger('prev.owl.carousel');
                    } else {
                        slider.trigger('to.owl.carousel', [value]);
                    }
                });
            })
        });
    })(jQuery, intec)
</script>
