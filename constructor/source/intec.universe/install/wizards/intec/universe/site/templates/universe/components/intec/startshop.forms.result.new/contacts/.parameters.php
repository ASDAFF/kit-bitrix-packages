<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = [
    'CONSENT_URL' => [
        'PARENT' => 'URL_TEMPLATES',
        'TYPE' => 'STRING',
        'NAME' => Loc::getMessage('C_STARTSHOP_FORMS_RESULT_NEW_CONTACTS_CONSENT_URL')
    ]
];