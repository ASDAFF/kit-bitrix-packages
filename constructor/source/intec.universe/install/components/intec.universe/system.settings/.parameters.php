<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arParameters = [
    'MODE' => [
        'NAME' => Loc::getMessage('C_SYSTEM_SETTINGS_MODE'),
        'PARENT' => 'BASE',
        'TYPE' => 'LIST',
        'VALUES' => [
            'configure' => Loc::getMessage('C_SYSTEM_SETTINGS_MODE_CONFIGURE'),
            'render' => Loc::getMessage('C_SYSTEM_SETTINGS_MODE_RENDER')
        ]
    ],
    'VARIABLES_ACTION' => [
        'NAME' => Loc::getMessage('C_SYSTEM_SETTINGS_VARIABLES_ACTION'),
        'PARENT' => 'BASE',
        'TYPE' => 'STRING',
        'DEFAULT' => 'system-settings-action'
    ]
];

$arComponentParameters = [
    'PARAMETERS' => $arParameters
];