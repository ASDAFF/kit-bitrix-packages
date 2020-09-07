<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

?>

<?= Html::beginTag('div', [
    'class' => [
        'basket-alert',
        'intec-ui' => [
            '',
            'control-alert',
            'scheme-orange'
        ]
    ]
]) ?>
    <?= Html::tag('div', null, [
        'class' => [
            'basket-alert-close',
            'far fa-times'
        ],
        'data-entity' => 'basket-item-close-restore-button'
    ]) ?>
    <div class="basket-alert-line">
        <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_RESTORE_PART_1')) ?>
        <?= Html::tag('span', '"{{NAME}}"') ?>
        <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_RESTORE_PART_2')) ?>
    </div>
    <div class="basket-alert-line">
        <?= Html::tag('span', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_RESTORE_ACTION'), [
            'class' => 'basket-alert-interactive-button',
            'data-entity' => 'basket-item-restore-button'
        ]) ?>
    </div>
<?= Html::endTag('div') ?>