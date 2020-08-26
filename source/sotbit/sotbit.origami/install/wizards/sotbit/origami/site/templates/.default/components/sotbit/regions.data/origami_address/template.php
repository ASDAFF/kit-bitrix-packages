<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();
if(is_array($arResult['FIELDS']['UF_ADDRESS']['VALUE']) && count($arResult['FIELDS']['UF_ADDRESS']['VALUE']) > 1):
    $address = reset($arResult['FIELDS']['UF_ADDRESS']['VALUE']);
    ?>
    <?=$address?>
<?elseif(is_array($arResult['FIELDS']['UF_ADDRESS']['VALUE'])):
    $address = reset($arResult['FIELDS']['UF_ADDRESS']['VALUE']);
    ?>
    <?=$address?>
<?else:
    $address = $arResult['FIELDS']['UF_ADDRESS']['VALUE'];
    ?>
	<?=$address?>
<?endif;?>