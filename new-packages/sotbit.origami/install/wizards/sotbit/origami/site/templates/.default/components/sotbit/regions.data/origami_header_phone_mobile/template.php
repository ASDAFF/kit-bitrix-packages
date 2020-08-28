<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();
if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE']) && count($arResult['FIELDS']['UF_PHONE']['VALUE']) > 1):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
    <div class="container_menu_mobile__phone_block">
        <a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>." class="container_menu_mobile__main_phone origami_icons_button">
            <svg class="" width="12" height="16">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
            </svg>
            <?=$phone?>
        </a>
        <div class="many_tels_wrapper">
            <div class="many_tels">
                <?
                foreach($arResult['FIELDS']['UF_PHONE']['VALUE'] as $i => $phone){
                    if($i == 0){
                        continue;
                    }?>
                    <a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>" class="container_menu_mobile__main_phone origami_icons_button">
                        <svg class="" width="12" height="16">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                        </svg>
                        <?=$phone?>
                    </a>
                <?}
                ?>
            </div>
        </div>
    </div>
<?elseif(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])):
    $phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
    ?>
    <div class="container_menu_mobile__phone_block">
        <a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>" class="container_menu_mobile__main_phone">
            <?=$phone?>
        </a>
    </div>
<?else:
    $phone = $arResult['FIELDS']['UF_PHONE']['VALUE'];
    ?>
    <div class="container_menu_mobile__phone_block">
        <a href="tel:<?=\SotbitOrigami::showDigitalPhone($phone)?>" class="container_menu_mobile__main_phone">
            <?=$phone?>
        </a>
    </div>
<?endif;?>