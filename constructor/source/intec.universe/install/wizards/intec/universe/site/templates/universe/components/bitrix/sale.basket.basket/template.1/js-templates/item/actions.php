<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\FileHelper;

?>
<div class="intec-grid-item-auto intec-grid-item-650-1" data-print="false">
    <div class="basket-item-actions">
        <div class="intec-grid intec-gri-nowrap intec-grid-a-v-stretch">
            <div class="intec-grid-item-auto intec-grid-item-650-1">
                <?= Html::tag('div', FileHelper::getFileData(__DIR__.'/../../svg/action.item.delete.svg'), [
                    'class' => 'basket-item-action',
                    'title' => Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_ITEM_ACTION_DELETE'),
                    'data' => [
                        'entity' => 'basket-item-delete',
                        'item-action' => 'delete'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
