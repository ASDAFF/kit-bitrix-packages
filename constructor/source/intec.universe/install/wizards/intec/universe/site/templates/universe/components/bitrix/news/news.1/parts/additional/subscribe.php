<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sPrefix = 'SUBSCRIBE_';
$sLength = StringHelper::length($sPrefix);

$arParameters = [];

$arExcluded = [
    'USE',
    'PAGES'
];

foreach ($arParams as $key => $sValue) {
    if (StringHelper::startsWith($key, $sPrefix)) {
        $key = StringHelper::cut($key, $sLength);

        if (!ArrayHelper::isIn($key, $arExcluded))
            $arParameters[$key] = $sValue;
    }
}

unset($key, $sValue);

?>
<div class="news-additional-subscribe news-additional-item">
    <div class="news-additional-subscribe-wrapper">
        <div class="news-additional-subscribe-content">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:subscribe.edit',
                'small.1',
                ArrayHelper::merge($arParameters, [
                    'AJAX_MODE' => 'N',
                    'AJAX_OPTION_STYLE' => 'N',
                    'AJAX_OPTION_HISTORY' => 'N',
                    'AJAX_OPTION_JUMP' => 'N',
                    'SET_TITLE' => 'N',
                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME']
                ])
            ) ?>
        </div>
    </div>
</div>
<?php unset($sPrefix, $sLength, $arParameters, $arExcluded) ?>