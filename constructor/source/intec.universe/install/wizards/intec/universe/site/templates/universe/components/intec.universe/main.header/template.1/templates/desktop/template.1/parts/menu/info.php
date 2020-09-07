<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$arMenu = $arResult['MENU']['INFO'];

?>
<?php if ($arMenu['SHOW']) { ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:menu',
        'info',
        [
            'ROOT_MENU_TYPE' => $arMenu['ROOT'],
            'CHILD_MENU_TYPE' => $arMenu['CHILD'],
            'MAX_LEVEL' => $arMenu['LEVEL'],
            'MENU_CACHE_TYPE' => 'N',
            'USE_EXT' => 'Y',
            'DELAY' => 'N',
            'ALLOW_MULTI_SELECT' => 'N'
        ],
        $this->getComponent()
    ); ?>
<?php } ?>
