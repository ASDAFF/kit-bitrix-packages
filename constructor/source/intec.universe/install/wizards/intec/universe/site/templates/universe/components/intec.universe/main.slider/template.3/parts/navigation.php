<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var bool $bSliderUse
 */

$bNavigationContain = false;

if ($arVisual['WIDE']) {
    $bNavigationContain = true;

    if ($arVisual['BLOCKS']['USE'] && $arVisual['BLOCKS']['POSITION'] === 'right')
        $bNavigationContain = false;
}

?>
<?php return function () use (&$arResult, &$arVisual, &$bSliderUse, &$bNavigationContain) { ?>
    <?php if ($bSliderUse) { ?>
        <?php if ($arVisual['SLIDER']['NAV']['SHOW']) { ?>
            <?= Html::tag('div', '', [
                'class' => Html::cssClassFromArray([
                    'widget-slider-nav' => true,
                    'intec-content' => [
                        '' => $bNavigationContain,
                        'visible' => $bNavigationContain
                    ]
                ], true),
                'data' => [
                    'role' => 'container.nav'
                ]
            ]) ?>
        <?php } ?>
        <?php if ($arVisual['SLIDER']['DOTS']['SHOW']) { ?>
            <?= Html::tag('div', '', [
                'class' => [
                    'widget-slider-dots'
                ],
                'data' => [
                    'role' => 'container.dots'
                ]
            ]) ?>
        <?php } ?>
    <?php } ?>
<?php } ?>