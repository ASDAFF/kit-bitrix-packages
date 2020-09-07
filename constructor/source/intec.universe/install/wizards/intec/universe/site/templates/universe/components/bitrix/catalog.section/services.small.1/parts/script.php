<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$arSlider = $arVisual['SLIDER'];
$arResponsive = [
    '0' => ['items' => 1]
];

if ($arVisual['WIDE']) {
    if ($arVisual['COLUMNS'] >= 2)
        $arResponsive['651'] = ['items' => 2];

    if ($arVisual['COLUMNS'] >= 3)
        $arResponsive['951'] = ['items' => 3];

    if ($arVisual['COLUMNS'] >= 4)
        $arResponsive['1151'] = ['items' => 4];
} else {
    if ($arVisual['COLUMNS'] >= 2)
        $arResponsive['751'] = ['items' => 2];

    if ($arVisual['COLUMNS'] >= 3)
        $arResponsive['1051'] = ['items' => 3];

    if ($arVisual['COLUMNS'] >= 4)
        $arResponsive['1201'] = ['items' => 4];
}

?>
<script type="text/javascript">
    (function ($, api) {
        $(function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var slider = $('[data-role="slider"]', root);

            <?php if ($arSlider['USE']) { ?>
                slider.owlCarousel(<?= JavaScript::toObject([
                    'items' => $arVisual['COLUMNS'],
                    'autoplay' => $arSlider['AUTO']['USE'],
                    'autoplaySpeed' => $arSlider['AUTO']['SPEED'],
                    'autoplayTimeout' => $arSlider['AUTO']['TIME'],
                    'autoplayHoverPause' => $arSlider['AUTO']['PAUSE'],
                    'loop' => $arSlider['LOOP'],
                    'nav' => $arSlider['NAVIGATION'],
                    'navText' => [
                        '<i class="far fa-chevron-left"></i>',
                        '<i class="far fa-chevron-right"></i>'
                    ],
                    'dots' => $arSlider['DOTS'],
                    'responsive' => $arResponsive,
                    'navContainerClass' => 'intec-ui intec-ui-control-navigation',
                    'navClass' => ['intec-ui-part-button-left', 'intec-ui-part-button-right'],
                    'dotsClass' => 'intec-ui intec-ui-control-dots',
                    'dotClass' => 'intec-ui-part-dot'
                ]) ?>);
            <?php } ?>
        });
    })(jQuery, intec);
</script>
<?php

unset($arResponsive);
unset($arSlider);