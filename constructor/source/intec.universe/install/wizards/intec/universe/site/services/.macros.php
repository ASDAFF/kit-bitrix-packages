<?php

use Bitrix\Main\ModuleManager;

return [
    'ORDER_PAGE_URL' => (
        ModuleManager::isModuleInstalled('sale')
    ) ? WIZARD_SITE_DIR.'personal/basket/order.php' : WIZARD_SITE_DIR.'personal/basket/?page=order'
];