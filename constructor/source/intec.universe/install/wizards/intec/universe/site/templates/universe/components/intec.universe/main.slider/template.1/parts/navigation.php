<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 * @var bool $bSliderUse
 */

?>
<?php if (!$bSliderUse) return ?>
<?php if ($arVisual['SLIDER']['NAV']['SHOW']) { ?>
    <?= Html::tag('div', '', [
        'class' => [
            'widget-slider-nav',
            'intec-content' => [
                '',
                'visible'
            ]
        ],
        'data' => [
            'role' => 'container.nav'
        ]
    ]) ?>
<?php } ?>
<?php if ($arVisual['SLIDER']['DOTS']['SHOW']) { ?>
    <?= Html::tag('div', '', [
        'class' => [
            'widget-slider-dots',
            'intec-content' => [
                '',
                'visible'
            ]
        ],
        'data' => [
            'role' => 'container.dots'
        ]
    ]) ?>
<?php } ?>