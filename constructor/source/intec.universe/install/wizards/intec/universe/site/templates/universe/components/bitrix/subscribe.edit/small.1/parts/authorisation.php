<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;

/**
 * @var array $arVisual
 */

?>
<div class="subscribe-edit-input">
    <div class="subscribe-edit-input-button">
        <?= Html::tag('a', Loc::getMessage('C_SUBSCRIBE_EDIT_SMALL_1_TEMPLATE_AUTHORISATION'), [
            'class' => [
                'subscribe-edit-authorisation',
                'intec-ui' => [
                    '',
                    'control-button',
                    'mod-round-half',
                    'mod-block',
                    'size-3',
                    'scheme-current'
                ]
            ],
            'href' => $arVisual['AUTHORISATION']['URL'],
            'target' => '_blank'
        ]) ?>
    </div>
</div>
