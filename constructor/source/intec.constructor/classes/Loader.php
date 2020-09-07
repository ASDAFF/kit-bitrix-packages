<?php

use Bitrix\Main\Loader;
use intec\Core;

if (Loader::includeModule('intec.core')) {
    Core::setAlias('@elements', '@bitrix/elements');
    Core::setAlias('@widgets', '@bitrix/widgets');
    Core::setAlias('@intec/constructor', __DIR__);
    Core::setAlias('@intec/constructor/module', dirname(__DIR__));
    Core::setAlias('@intec/constructor/resources', '@resources/intec.constructor');
    Core::setAlias('@intec/constructor/upload', '@upload/intec/constructor');

    require(__DIR__.'/web.php');
}