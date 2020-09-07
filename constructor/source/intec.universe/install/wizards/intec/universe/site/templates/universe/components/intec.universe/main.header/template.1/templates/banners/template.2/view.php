<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sPrefix = 'BANNER_';
$arParameters = [];

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        $arParameters[$sKey] = $sValue;
    }

$arParameters['SELECTOR'] = '#'.$sTemplateId;
$arParameters['ATTRIBUTE'] = 'data-color';

?>
<div class="widget-banner-2">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.slider',
        'template.2',
        $arParameters,
        $this->getComponent()
    ) ?>
</div>