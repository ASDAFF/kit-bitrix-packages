<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\StringHelper;

/**
 * @var array $arCurrentValues
 * @var string $siteTemplate
 */

$arReturn = Component::getParameters(
    'intec.universe:main.slider',
    'template.2',
    $siteTemplate,
    $arCurrentValues,
    'BANNER_',
    function ($sKey, &$arParameter) {
        $arParameter['NAME'] = Loc::getMessage('C_HEADER_TEMP1_BANNERS_TEMP2_BANNER').' '.$arParameter['NAME'];

        if (StringHelper::startsWith($sKey, 'CACHE'))
            return false;

        if (StringHelper::startsWith($sKey, 'COMPOSITE'))
            return false;

        return true;
    }
);

return $arReturn;