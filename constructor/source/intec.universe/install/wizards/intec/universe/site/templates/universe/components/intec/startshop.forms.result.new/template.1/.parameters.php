<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = [
    'SHOW_TITLE' => [
        'TYPE' => 'CHECKBOX',
        'NAME' => Loc::getMessage('SFRN_DEFAULT_SHOW_TITLE')
    ],
    'CONSENT_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING',
        'NAME' => Loc::getMessage('SFRN_DEFAULT_CONSENT_URL')
    ]
];