<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 */

$sPrefix = 'FORM_';
$arForm['PARAMETERS'] = [];

foreach ($arParams as $sKey => $mValue) {
    if (!StringHelper::startsWith($sKey, $sPrefix))
        continue;

    $sKey = StringHelper::cut(
        $sKey,
        StringHelper::length($sPrefix)
    );

    $arForm['PARAMETERS'][$sKey] = $mValue;
}

?>
<div class="news-detail-form">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.widget',
        'form.5',
        $arForm['PARAMETERS'],
        $component
    ) ?>
</div>