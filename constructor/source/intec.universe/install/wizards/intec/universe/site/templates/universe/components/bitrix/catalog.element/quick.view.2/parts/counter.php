<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 */

?>
<div class="catalog-element-counter">
    <div class="intec-ui intec-ui-control-numeric intec-ui-view-3 intec-ui-scheme-current intec-ui-size-4" data-role="counter">
        <?= Html::tag('a', '-', [
            'class' => 'intec-ui-part-decrement',
            'href' => 'javascript:void(0)',
            'data-type' => 'button',
            'data-action' => 'decrement'
        ]) ?>
        <?= Html::input('text', null, 0, [
            'data-type' => 'input',
            'class' => 'intec-ui-part-input'
        ]) ?>
        <?= Html::tag('a', '+', [
            'class' => 'intec-ui-part-increment',
            'href' => 'javascript:void(0)',
            'data-type' => 'button',
            'data-action' => 'increment'
        ]) ?>
    </div>
</div>