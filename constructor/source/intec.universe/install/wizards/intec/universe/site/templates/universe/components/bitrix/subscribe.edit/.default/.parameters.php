<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = [
    'CONSENT_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING',
        'NAME' => Loc::getMessage('SE_DEFAULT_CONSENT_URL')
    ]
];