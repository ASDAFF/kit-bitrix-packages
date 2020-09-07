<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\Html;

?>
<?php $vCounter = function () { ?>
        <div class="intec-ui intec-ui-control-numeric intec-ui-view-4" data-role="item.counter">
            <?= Html::tag('a', '-', [
                'class' => [
                    'intec-ui-part-decrement',
                    'intec-cl-text-hover'
                ],
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'decrement'
            ]) ?>
            <?= Html::input('text', null, 0, [
                'data-type' => 'input',
                'class' => 'intec-ui-part-input'
            ]) ?>
            <?= Html::tag('a', '+', [
                'class' => [
                    'intec-ui-part-increment',
                    'intec-cl-text-hover'
                ],
                'href' => 'javascript:void(0)',
                'data-type' => 'button',
                'data-action' => 'increment'
            ]) ?>
        </div>
<?php };