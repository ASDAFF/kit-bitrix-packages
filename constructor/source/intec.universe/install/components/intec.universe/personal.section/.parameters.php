<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;

$arParameters = array(
    'URL_PERSONAL_DATA' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('PS_URL_PERSONAL_DATA'),
        'TYPE' => 'STRING'
    ),
    'URL_BASKET' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('PS_URL_BASKET'),
        'TYPE' => 'STRING'
    ),
    'URL_ORDER' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('PS_URL_ORDER'),
        'TYPE' => 'STRING'
    ),
    'URL_CONTACTS' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('PS_URL_CONTACTS'),
        'TYPE' => 'STRING'
    )
);

$arComponentParameters = array(
    'PARAMETERS' => $arParameters
);
