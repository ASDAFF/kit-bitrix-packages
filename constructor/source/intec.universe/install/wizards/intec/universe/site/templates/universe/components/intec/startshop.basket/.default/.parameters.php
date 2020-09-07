<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!CModule::IncludeModule('intec.core'))
    return;

if (!CModule::IncludeModule('intec.startshop'))
    return;

Loc::loadMessages(__FILE__);

$arTemplateParameters['USE_ADAPTABILITY'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_ADAPTABILITY'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);
$arTemplateParameters['USE_ITEMS_PICTURES'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_ITEMS_PICTURES'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
);
$arTemplateParameters['USE_SUM_FIELD'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_SUM_FIELD'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);


// Buttons
$arTemplateParameters['USE_BUTTON_CLEAR'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_BUTTON_CLEAR'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);
$arTemplateParameters['USE_BUTTON_BASKET'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_BUTTON_BASKET'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);
$arTemplateParameters['USE_BUTTON_FAST_ORDER'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_BUTTON_FAST_ORDER'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);
$arTemplateParameters['USE_BUTTON_CONTINUE_SHOPPING'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_USE_BUTTON_CONTINUE_SHOPPING'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);
$arTemplateParameters['VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('SB_DEFAULT_VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['USE_BUTTON_CONTINUE_SHOPPING'] == 'Y') {
    $arTemplateParameters['URL_CATALOG'] = array(
        'PARENT' => 'URL',
        'NAME' => GetMessage('SB_DEFAULT_URL_CATALOG'),
        'TYPE' => 'STRING'
    );
}

if ($arCurrentValues['VERIFY_CONSENT_TO_PROCESSING_PERSONAL_DATA'] == 'Y') {
    $arTemplateParameters['URL_RULES_OF_PERSONAL_DATA_PROCESSING'] = array(
        'PARENT' => 'URL',
        'NAME' => GetMessage('SB_DEFAULT_URL_RULES_OF_PERSONAL_DATA_PROCESSING'),
        'TYPE' => 'STRING'
    );
}

include(__DIR__.'/parameters/order.fast.php');