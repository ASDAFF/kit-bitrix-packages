<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

$vAdditionalView = include(__DIR__.'/additional/view.'.$arVisual['ADDITIONAL']['VIEW'].'.php');

?>
<?php return function (&$arData) use (&$arVisual, &$vAdditionalView) { ?>
    <?php if (!$arVisual['ADDITIONAL']['SHOW']) return ?>
    <?= Html::beginTag('div', [
        'class' => [
            'widget-item-content-additional',
            'intec-grid'
        ],
        'data' => [
            'view' => $arVisual['ADDITIONAL']['VIEW']
        ]
    ]) ?>
        <div class="widget-item-additional-items intec-grid">
            <?php if (!empty($arData['ADDITIONAL'])) {
                $vAdditionalView($arData);
            } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>