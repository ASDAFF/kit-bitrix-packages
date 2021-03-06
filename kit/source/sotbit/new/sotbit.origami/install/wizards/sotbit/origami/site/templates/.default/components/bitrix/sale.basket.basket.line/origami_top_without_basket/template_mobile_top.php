<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Sotbit\Origami\Config\Option;
/*if($arParams["AJAX"]=="Y")
{
    if($arResult["SHOW_BASKET"]):
    ?>
    <div id="basket_top_mobile_cont_from" style="display:none">
        <a class="basket-block__link fal fa-shopping-basket basket-block__link_main_basket"
           href="<?=$arParams['PATH_TO_BASKET']?>" onmouseenter="<?=$cartId?>.toggleOpenCloseCart('open')">
            <?

            ?>
            <span class="basket-block__link_basket_cal" ><?=$arResult["NUM_PRODUCTS"]?></span>
            <?

            ?>
        </a>
    </div>
    <?endif;?>
    <?if($arResult["SHOW_COMPARE"] || $arResult["SHOW_DELAY"] || $arResult["SHOW_BASKET"]):?>
    <div id="basket_top_mobile_menu_from" style="display:none">
        <div class="container_menu_mobile__item fonts__small_text">
            <a class="container_menu_mobile__item_link" href="<?=Config::get('PERSONAL_PAGE')?>">
                <span class="icon-locked"></span>
                <?=Loc::getMessage('MENU_PERSONAL_ACCOUNT');?>
            </a>
        </div>
        <?if($arResult["SHOW_COMPARE"]):?>
        <div class="container_menu_mobile__item fonts__small_text">
            <a class="container_menu_mobile__item_link" href="<?=Config::get('COMPARE_PAGE')?>">
                <span class="fal fa-chart-bar"></span>
                <?=Loc::getMessage('MENU_COMPARISON');?>
                <span class="container_menu_mobile__item_link_col"><?=$arResult["NUM_PRODUCTS_COMPARE"]?></span>
            </a>
        </div>
        <?endif;?>
        <?if($arResult["SHOW_DELAY"]):?>
        <div class="container_menu_mobile__item fonts__small_text">
            <a class="container_menu_mobile__item_link" href="<?=Config::get('BASKET_PAGE')?>">
                <span class="fal fa-heart"></span>
                <?=Loc::getMessage('MENU_FAVOURITES');?>
                <span class="container_menu_mobile__item_link_col"><?=$arResult["NUM_PRODUCTS_DELAY"]?></span>
            </a>
        </div>
        <?endif;?>
        <?if($arResult["SHOW_BASKET"]):?>
        <div class="container_menu_mobile__item fonts__small_text">
            <a class="container_menu_mobile__item_link" href="<?=Config::get('BASKET_PAGE')?>">
                <span class="fal fa-shopping-basket"></span>
                <?=Loc::getMessage('MENU_CART');?>
                <span class="container_menu_mobile__item_link_col"><?=$arResult["NUM_PRODUCTS"]?></span>
            </a>
        </div>
        <?endif?>
        <?if($USER->IsAuthorized()):?>
            <div class="container_menu_mobile__item fonts__small_text">
                <a class="container_menu_mobile__item_link" href="?logout=yes">
                    <span class="fal fa-sign-out"></span>
                    <?=Loc::getMessage('MENU_LOGOUT');?>
                </a>
            </div>
        <?endif?>
    </div>
    <?endif;?>
    <?
} else

    */
    if($arResult["SHOW_BASKET"]){
    ?>
    <?
    $this->SetViewTarget('basket_mobile_top');?>
     <a class="header_top_block_cabinet__title fonts__small_text origami_icons_button" href="<?=$arParams['PATH_TO_PROFILE']?>">
        <div class="icon-locked"></div>
        <?=htmlspecialcharsbx($name)?>
    </a>

    <a class="basket-block__link basket-block__compare" href="<?=Config::get('COMPARE_PAGE')?>">
        <svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M424 352h16c4.4 0 8-3.6 8-8V104c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v240c0 4.4 3.6 8 8 8zm-96 0h16c4.4 0 8-3.6 8-8V200c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v144c0 4.4 3.6 8 8 8zm-192 0h16c4.4 0 8-3.6 8-8v-80c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v80c0 4.4 3.6 8 8 8zm96 0h16c4.4 0 8-3.6 8-8V136c0-4.4-3.6-8-8-8h-16c-4.4 0-8 3.6-8 8v208c0 4.4 3.6 8 8 8zm272 64H32V72c0-4.42-3.58-8-8-8H8c-4.42 0-8 3.58-8 8v360c0 8.84 7.16 16 16 16h488c4.42 0 8-3.58 8-8v-16c0-4.42-3.58-8-8-8z"/></svg>
        <div class="basket-block__link_basket_cal" ><?=$arResult["NUM_PRODUCTS_COMPARE"]?></div>
    </a>
    <a class="basket-block__link basket-block__wish" href="<?=$arParams['PATH_TO_BASKET']?>" <?if($arResult["NUM_PRODUCTS_DELAY"]>0):?>onmouseenter="<?=$cartId?>.toggleOpenCloseCart('open')"<?endif;?>>
        <svg height="20px" width="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M462.3 62.7c-54.5-46.4-136-38.7-186.6 13.5L256 96.6l-19.7-20.3C195.5 34.1 113.2 8.7 49.7 62.7c-62.8 53.6-66.1 149.8-9.9 207.8l193.5 199.8c6.2 6.4 14.4 9.7 22.6 9.7 8.2 0 16.4-3.2 22.6-9.7L472 270.5c56.4-58 53.1-154.2-9.7-207.8zm-13.1 185.6L256.4 448.1 62.8 248.3c-38.4-39.6-46.4-115.1 7.7-161.2 54.8-46.8 119.2-12.9 142.8 11.5l42.7 44.1 42.7-44.1c23.2-24 88.2-58 142.8-11.5 54 46 46.1 121.5 7.7 161.2z"/></svg>
        <div class="basket-block__link_basket_cal" ><?=$arResult["NUM_PRODUCTS_DELAY"]?></div>
    </a>
    <a class="basket-block__link basket-block__link_main_basket"
        href="<?=$arParams['PATH_TO_BASKET']?>" onmouseenter="<?=$cartId?>.toggleOpenCloseCart('open')">
        <?

        ?>
		<svg height="20px" width="20px" viewBox="0 0 25 21" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.80018 19.1682C3.87367 19.907 4.48899 20.4645 5.23151 20.4645H20.2334C20.9759 20.4645 21.5919 19.907 21.6669 19.1684L21.6669 19.1683L22.6503 9.30659C22.6503 9.30659 22.6503 9.30658 22.6503 9.30657C22.6623 9.18616 22.6229 9.0648 22.5409 8.97493C22.4591 8.88469 22.343 8.83305 22.2216 8.83305H3.24481C3.12255 8.83305 3.00717 8.88475 2.92551 8.97492C2.84351 9.06477 2.80404 9.18541 2.81607 9.30654L2.81607 9.30658L3.80018 19.1682ZM3.80018 19.1682L3.8748 19.1608L3.80018 19.1683L3.80018 19.1682ZM4.65623 19.083L4.65623 19.083L3.71946 9.69482H21.7455L20.8087 19.083L20.8087 19.083C20.7791 19.3802 20.532 19.6035 20.2334 19.6035H5.2308C4.93233 19.6035 4.68583 19.3803 4.65623 19.083Z" stroke-width="0.15"></path>
                <path d="M10.571 1.02625L10.5713 1.02603C10.754 0.874217 11.0252 0.896684 11.1773 1.07966C11.3305 1.26153 11.3063 1.53352 11.1238 1.68589L11.1239 1.68584L11.0757 1.62838L11.1238 1.68595L6.68264 5.41179L6.68263 5.41179C6.60268 5.47885 6.50446 5.51252 6.4069 5.51252C6.28404 5.51252 6.1615 5.46007 6.07687 5.35816L10.571 1.02625ZM10.571 1.02625L6.12987 4.75209C6.12986 4.7521 6.12984 4.75211 6.12983 4.75212L10.571 1.02625Z" stroke-width="0.15"></path>
                <path d="M18.7823 5.4116L18.7826 5.41185C18.8631 5.47862 18.9611 5.51233 19.0588 5.51233C19.1817 5.51233 19.304 5.45984 19.3893 5.35826L19.3894 5.35812C19.5418 5.1756 19.5176 4.90453 19.3361 4.75143L19.3359 4.75129L14.8939 1.02534L14.8938 1.02523C14.7112 0.872769 14.441 0.897174 14.2872 1.07837L14.2872 1.07837L14.2869 1.07882C14.1345 1.26134 14.1587 1.53241 14.3402 1.68551L14.3403 1.68565L18.7823 5.4116ZM19.2877 4.80875C19.4377 4.93532 19.4577 5.15931 19.3318 5.31005C19.2614 5.39396 19.1604 5.43733 19.0588 5.43733C18.9784 5.43733 18.8973 5.4096 18.8305 5.35414L19.2877 4.80875Z" stroke-width="0.15"></path>
                <path d="M2.22658 9.69479H23.2384C23.9568 9.69479 24.54 9.11082 24.54 8.39321V6.11925C24.54 5.40161 23.956 4.81767 23.2384 4.81767H2.22658C1.50894 4.81767 0.925 5.40161 0.925 6.11925V8.39321C0.925 9.11085 1.50894 9.69479 2.22658 9.69479ZM1.78606 6.11854C1.78606 5.87553 1.98357 5.67802 2.22658 5.67802H23.2384C23.4814 5.67802 23.6789 5.87553 23.6789 6.11854V8.3925C23.6789 8.63544 23.4807 8.83302 23.2384 8.83302H2.22658C1.98357 8.83302 1.78606 8.6355 1.78606 8.3925V6.11854Z" stroke-width="0.15"></path>
                <path d="M7.15031 17.1286C7.15031 17.3662 7.34317 17.5591 7.58084 17.5591C7.81923 17.5591 8.01137 17.3669 8.01137 17.1286V11.9734C8.01137 11.7357 7.81851 11.5428 7.58084 11.5428C7.34317 11.5428 7.15031 11.7357 7.15031 11.9734V17.1286Z" stroke-width="0.15"></path>
                <path d="M10.5847 17.1286C10.5847 17.3662 10.7776 17.5591 11.0153 17.5591C11.2537 17.5591 11.4458 17.3669 11.4458 17.1286V11.9734C11.4458 11.7357 11.2529 11.5428 11.0153 11.5428C10.7776 11.5428 10.5847 11.7357 10.5847 11.9734V17.1286Z" stroke-width="0.15"></path>
                <path d="M14.0192 17.1286C14.0192 17.3662 14.212 17.5591 14.4497 17.5591C14.6873 17.5591 14.8802 17.367 14.8802 17.1286V11.9734C14.8802 11.7357 14.6874 11.5428 14.4497 11.5428C14.212 11.5428 14.0192 11.7357 14.0192 11.9734V17.1286Z" stroke-width="0.15"></path>
                <path d="M17.4536 17.1286C17.4536 17.3662 17.6464 17.5591 17.8841 17.5591C18.1217 17.5591 18.3146 17.367 18.3146 17.1286V11.9734C18.3146 11.7357 18.1218 11.5428 17.8841 11.5428C17.6464 11.5428 17.4536 11.7357 17.4536 11.9734V17.1286Z" stroke-width="0.15"></path>
            </svg>
        <span class="basket-block__link_basket_cal" ><?=$arResult["NUM_PRODUCTS"]?></span>
        <?

        ?>
    </a>
    <?$this->EndViewTarget();
}
?>
