<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    'NAME' => Loc::getMessage('c.intec.constructor.block.name'),
    'DESCRIPTION' => Loc::getMessage('c.intec.constructor.block.description'),
    'CACHE_PATH' => 'Y',
    'SORT' => 1,
    'PATH' => [
        'ID' => 'intec.constructor',
        'NAME' => Loc::getMessage('c.intec.constructor.block.category')
    ]
];