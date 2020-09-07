<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $serverName; ?>
    <td width="16.6%"><a href="<?=$serverName?>/catalog/"
                         style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;"><?=Getmessage('CATALOG_MENU')?></a></td>
<? if (!empty($arResult)) {

    foreach ($arResult as $arItem) {

        if ($arItem['DEPTH_LEVEL'] > 1 || !$arItem['PARAMS']['FROM_IBLOCK']) {
            continue;
        }
        ?>

        <td width="16.6%"><a href="<?=$serverName . $arItem["LINK"]?>?utm_source=newsletter&utm_medium=email&utm_campaign=b2bshop_mailing"
                    target="_blank"
                    style="color: #000000; font-family: 'Open Sans', sans-serif; font-size: 14px; text-decoration: none;">
                <?= $arItem["TEXT"] ?></a>
        </td>
    <? } ?>

<? } ?>