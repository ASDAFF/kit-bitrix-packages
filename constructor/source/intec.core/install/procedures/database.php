<?php

use Bitrix\Main\Localization\Loc;
use intec\core\db\Query;
use intec\core\db\Exception;

try {
    $query = new Query();
    $query->select('*');
    $query->from(['b_module']);
    $query->all();
} catch (Exception $exception) {
    $APPLICATION->IncludeAdminFile(
        Loc::getMessage('intec.core.installer.database.title'),
        __DIR__.'/../database.php'
    );
    exit;
}