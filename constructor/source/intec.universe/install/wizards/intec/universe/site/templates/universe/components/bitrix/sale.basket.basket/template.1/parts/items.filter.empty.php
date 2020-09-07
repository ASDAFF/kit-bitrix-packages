<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

?>
<?= Html::beginTag('div', [
    'id' => 'basket-item-list-empty-result',
    'class' => 'basket-filter-empty',
    'style' => [
        'display' => 'none'
    ]
]) ?>
    <div class="basket-filter-empty-picture">
        <?= FileHelper::getFileData(__DIR__.'/../svg/filter.empty.svg') ?>
    </div>
    <div class="basket-filter-empty-title">
        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_FILTER_EMPTY_TITLE') ?>
    </div>
    <div class="basket-filter-empty-description">
        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_FILTER_EMPTY_DESCRIPTION') ?>
    </div>
<?= Html::endTag('div') ?>