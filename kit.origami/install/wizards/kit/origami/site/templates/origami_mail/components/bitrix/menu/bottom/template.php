<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult))
{
	global $serverName;?>
    <tr>
                    <td style="padding-bottom: 20px;">
                        <a href="/catalog/"
                           style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;"><?=Getmessage('CATALOG_MENU')?></a>
                    </td>
    </tr>
	<?foreach($arResult as $i=>$arItem)
	{
			?>
        <tr><td style="padding-bottom: 20px;"><a href="<?=$arItem["LINK"]?>?utm_source=newsletter&utm_medium=email&utm_campaign=b2bshop_mailing" target="_blank" style="color: #ffffff; font-family: 'Open Sans', sans-serif; font-size: 13px; text-decoration: none;"><?=$arItem['TEXT'] ?></a></td></tr>
			<?

	}?>

<?}?>