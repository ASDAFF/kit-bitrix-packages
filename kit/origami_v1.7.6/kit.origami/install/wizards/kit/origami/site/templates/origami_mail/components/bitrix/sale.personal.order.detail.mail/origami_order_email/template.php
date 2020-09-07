<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
       style="padding-top: 40px;">
    <tr>
        <td bgcolor="#f7f7f7" height="40" colspan="4">
            <p style="color: #252525; font-family: 'Open Sans', sans-serif; font-size: 14px; font-weight: bold; padding-left: 25px;">
                <?=$arResult['ALL_QUANTITY']?> <span
                        style="color: #8b8b8b; font-weight: normal;"><?=GetMessage('SPOD_ORDER_PRICE');?> </span><?=$arResult['TOTAL_PRICE']?>
                &#8381;</p>
        </td>
    </tr>

    <?foreach ($arResult["BASKET"] as $prod):?>
        <tr>
            <td style="padding-top: 20px; padding-left: 20px; padding-bottom: 20px; padding-right: 20px; border-bottom: 1px solid #f2f2f2; border-left: 1px solid #f2f2f2;">
                <div style="border: 1px solid #ececec; width: 105px; height: 105px; display: table;">
                    <a href="<?=$serverName ?><?=$prod['DETAIL_PAGE_URL']?>"
                       style="line-height: 105px; display: table-cell; vertical-align: middle;"><img
                                src="<?=$prod['PICTURE']['SRC']?>" alt="" border="0" width="75" height=""
                                style="display:block;text-decoration:none;outline:none; margin: auto; vertical-align: middle;"></a>
                </div>
            </td>
            <td style="border-bottom: 1px solid #f2f2f2;">
                <a href="<?=$serverName ?><?=$prod['DETAIL_PAGE_URL']?>" target="_blank"
                   style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 15px; font-weight: bold; text-decoration: none;"><?= $prod['NAME'] ?></a>
                <p>
                    <?if (!empty($prod['PROPS'])) {?>
                    <?foreach ($prod['PROPS'] as $prop):?>
                        <span style="color: #060606; font-family: 'Open Sans', sans-serif; font-size: 14px; padding-right: 40px;"><?= $prop['NAME'] ?>
                            : <?= $prop['VALUE'] ?></span>
                    <?endforeach;?>
            <?}?>
                </p>
            </td>
            <td align="center" style="border-bottom: 1px solid #f2f2f2;">
                <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 13px;">
                    <?=$prod['QUANTITY']?> <?=$prod['MEASURE_TEXT']?>.</p>
            </td>
            <td align="center"
                style="border-bottom: 1px solid #f2f2f2; border-right: 1px solid #f2f2f2;">
                <p style="color: #313131; font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: bold;">
                    <?echo $prod['PRICE'];?> &#8381;</p>
            </td>
        </tr>
    <?endforeach;?>
</table>
