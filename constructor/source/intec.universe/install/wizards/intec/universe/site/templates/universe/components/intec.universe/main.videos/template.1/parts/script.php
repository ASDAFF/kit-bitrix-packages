<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arVisual
 * @var array $arResponsiveReady
 * @var string $sTemplateId
 */

$arSlider = $arVisual['SLIDER'];
$arResponsive = [
    '0' => ['items' => 1]
];

if ($arVisual['COLUMNS'] >= 2)
    $arResponsive['551'] = ['items' => 2];

if ($arVisual['COLUMNS'] >= 3)
    $arResponsive['751'] = ['items' => 3];

if ($arVisual['COLUMNS'] >= 4)
    $arResponsive['901'] = ['items' => 4];

if ($arVisual['COLUMNS'] >= 5)
    $arResponsive['1051'] = ['items' => 5];

?>
<script>
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var gallery = $('[data-entity="gallery"]', root);
        var slider = $('[data-role="slider"]', root);

        <?php if (!defined('EDITOR')) { ?>
            gallery.lightGallery({
                'selector': '[data-play="true"]'
            });
        <?php } ?>

        <?php if ($arSlider['USE']) { ?>
            slider.owlCarousel(<?= JavaScript::toObject([
                'items' => $arVisual['COLUMNS'],
                'autoplay' => $arSlider['AUTO']['USE'],
                'autoplaySpeed' => $arSlider['AUTO']['SPEED'],
                'autoplayTimeout' => $arSlider['AUTO']['TIME'],
                'autoplayHoverPause' => $arSlider['AUTO']['PAUSE'],
                'loop' => $arSlider['LOOP'],
                'nav' => false,
                'navText' => ['', ''],
                'dots' => true,
                'responsive' => $arResponsive
            ]) ?>);
        <?php } ?>
    })(jQuery, intec);
</script>
<?php

unset($arResponsive);
unset($arSlider);