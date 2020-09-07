<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

$sPrefix = 'BUTTON_SOCIAL_';
$sLength = StringHelper::length($sPrefix);

$arParameters = [];

$arExcluded = [
    'SHOW'
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
<div class="news-detail-social-wrap">
    <div class="news-detail-social">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.share',
            'flat',
            ArrayHelper::merge($arParameters, [
                'PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
                'PAGE_TITLE' => $arResult['NAME']
            ]),
            $component
        ) ?>
    </div>
</div>
<?php unset($sPrefix, $sLength, $arParameters, $arExcluded) ?>