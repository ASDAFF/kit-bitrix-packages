<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$sIcon = FileHelper::getFileData(__DIR__.'/../../svg/order.fast.svg');

?>
<?= Html::beginTag('div', [
    'class' => 'intec-grid-item-auto',
    'data-disable' => ''
]) ?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-element-order-fast',
        'data-role' => 'orderFast'
    ]) ?>
        <?= $sIcon ?>
        <?= Html::tag('span', Loc::getMessage('C_CATALOG_ELEMENT_DEFAULT_3_TEMPLATE_BUTTON_ORDER_FAST')) ?>
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>
<?php unset($sIcon) ?>