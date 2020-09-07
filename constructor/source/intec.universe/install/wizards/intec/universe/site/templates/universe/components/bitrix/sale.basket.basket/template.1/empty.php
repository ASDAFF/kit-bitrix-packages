<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 */

?>
<div class="basket-empty">
    <div class="basket-empty-picture">
        <?= FileHelper::getFileData(__DIR__.'/svg/empty.svg') ?>
    </div>
    <div class="basket-empty-title">
        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_EMPTY_TITLE') ?>
    </div>
    <div class="basket-empty-description">
        <?= Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_EMPTY_DESCRIPTION') ?>
    </div>
    <div class="basket-empty-button">
        <?= Html::tag('a', Loc::getMessage('C_BASKET_DEFAULT_1_TEMPLATE_EMPTY_URL'), [
            'class' => [
                'intec-ui' => [
                    '',
                    'control-button',
                    'size-5',
                    'mod-round-4',
                    'scheme-current'
                ]
            ],
            'href' => StringHelper::replaceMacros($arParams['EMPTY_BASKET_HINT_PATH'], [
                'SITE_DIR' => SITE_DIR
            ])
        ]) ?>
    </div>
</div>