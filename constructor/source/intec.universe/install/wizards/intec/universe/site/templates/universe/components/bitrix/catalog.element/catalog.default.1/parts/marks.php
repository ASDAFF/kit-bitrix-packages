<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:main.markers',
    'template.1', [
        'RECOMMEND' => $arResult['MARKS']['RECOMMEND'] ? 'Y' : 'N',
        'NEW' => $arResult['MARKS']['NEW'] ? 'Y' : 'N',
        'HIT' => $arResult['MARKS']['HIT'] ? 'Y' : 'N',
        'ORIENTATION' => $arVisual['GALLERY']['SHOW'] ? 'vertical' : 'horizontal'
    ],
    $component,
    ['HIDE_ICONS' => 'Y']
) ?>