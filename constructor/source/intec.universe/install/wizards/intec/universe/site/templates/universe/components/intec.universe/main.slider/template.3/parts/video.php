<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arData
 * @var CMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php return function (&$arData, $sBackground = null) use (&$APPLICATION, &$component) { global $APPLICATION?>
    <?php if (!empty($arData['VIDEO']['FILES'])) { ?>
        <div class="widget-item-video">
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:system.video.tag',
                '.default', [
                    'FILES_MP4' => !empty($arData['VIDEO']['FILES']['MP4']) ? $arData['VIDEO']['FILES']['MP4']['SRC'] : null,
                    'FILES_WEBM' => !empty($arData['VIDEO']['FILES']['WEBM']) ? $arData['VIDEO']['FILES']['WEBM']['SRC'] : null,
                    'FILES_OGV' => !empty($arData['VIDEO']['FILES']['OGV']) ? $arData['VIDEO']['FILES']['OGV']['SRC'] : null,
                    'PICTURE' => !empty($sBackground) ? $sBackground : null,
                    'CACHE_TYPE' => 'N'
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            ) ?>
        </div>
    <?php } else if (!empty($arData['VIDEO']['LINK'])) { ?>
        <div class="widget-item-video">
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:system.video.frame',
                '.default', [
                    'URL' => $arData['VIDEO']['LINK'],
                    'CACHE_TYPE' => 'N'
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            ) ?>
        </div>
    <?php } ?>
<?php } ?>