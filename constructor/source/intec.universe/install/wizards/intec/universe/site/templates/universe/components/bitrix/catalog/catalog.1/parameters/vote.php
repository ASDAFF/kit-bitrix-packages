<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arParametersCommon
 * @var string $componentName
 * @var string $componentTemplate
 * @var string $siteTemplate
 */

$arTemplateParameters['VOTE_MODE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_CATALOG_CATALOG_1_VOTE_MODE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'rating' => Loc::getMessage('C_CATALOG_CATALOG_1_VOTE_MODE_RATING'),
        'average' => Loc::getMessage('C_CATALOG_CATALOG_1_VOTE_MODE_AVERAGE')
    ],
    'DEFAULT' => 'left'
];