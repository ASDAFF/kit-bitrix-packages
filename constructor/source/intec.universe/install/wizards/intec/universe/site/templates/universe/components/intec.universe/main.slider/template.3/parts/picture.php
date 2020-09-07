<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<?php return function ($arData) use ($arVisual) { ?>
    <?= Html::beginTag('div', [
        'class' => [
            'widget-item-picture',
            'intec-grid-item' => [
                '2',
                'a-stretch'
            ]
        ]
    ]) ?>
        <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $arData['PICTURE']['VALUE']['SRC'], [
            'title' => '',
            'alt' => '',
            'data' => [
                'align-vertical' => $arData['PICTURE']['ALIGN']['VERTICAL'],
                'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                'original' => $arVisual['LAZYLOAD']['USE'] ? $arData['PICTURE']['VALUE']['SRC'] : null
            ]
        ]) ?>
    <?= Html::endTag('div') ?>
<?php } ?>