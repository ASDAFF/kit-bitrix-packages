<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
$this->createFrame()->begin();
Loc::loadMessages(__FILE__);

if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])){
	$phone = reset($arResult['FIELDS']['UF_PHONE']['VALUE']);
}
else{
	$phone = $arResult['FIELDS']['UF_PHONE']['VALUE'];
}
?>

<div class="contact-phone__link">
    <svg class="contact-phone__link-icon" width="18" height="18">
        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone"></use>
    </svg>
    <?=$phone?>
    <svg class="contact-phone__arrow-icon" width="10" height="6">
        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
    </svg>
</div>
<?if(Config::get("HEADER_CALL") == "Y"):?>
<!-- <a href="javascript:callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>')"> -->
<?endif;?>
<div class="contact-phone__drop-down">
    <div class="contact-phone__drop-down-item contact-phone__drop-down-item--phone">
        <svg width="18" height="18">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone"></use>
        </svg>
        <?
        if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])):
            foreach($arResult['FIELDS']['UF_PHONE']['VALUE'] as $tel):?>
                <a class="contact-phone__link-phone" href="tel:<?=$tel?>"><?=$tel?></a>
            <?endforeach;
        else:?>
            <a class="contact-phone__link-phone" href="tel:<?=$arResult['FIELDS']['UF_PHONE']['VALUE']?>">
                <?=$arResult['FIELDS']['UF_PHONE']['VALUE']?>
            </a>
        <?endif;?>
    </div>
    <div class="contact-phone__drop-down-item">
        <svg width="18" height="20">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_time"></use>
        </svg>
        <p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_DIR."include/sotbit_origami/contacts_worktime.php")
            );
            ?>
        </p>
    </div>
    <div class="contact-phone__drop-down-item">
        <svg width="18" height="20">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail"></use>
        </svg>
        <?
        if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])):
            foreach($arResult['FIELDS']['UF_EMAIL']['VALUE'] as $email):?>
                <a href="mailto:<?=$email?>" title="<?=$email?>">
                    <?=$email?>
                </a>
            <?endforeach;
        else:?>
            <a href="mailto:<?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>" title="<?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>">
                <?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>
            </a>
        <?endif;?>
    </div>
    <div class="contact-phone__drop-down-item">
        <svg width="18" height="20">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_pin"></use>
        </svg>
        <p><?=$arResult['FIELDS']['UF_ADDRESS']['VALUE']?></p>
    </div>
    <?if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')):?>
            <!-- <a class="header-two__drop-down-btn" href="javascript:callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>')"> -->
        <a a href="javascript:void(0)" class="contact-phone__drop-down-btn" onclick="callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>',this)">
            <?=Loc::getMessage('HEADER_3_CALL_PHONE')?>
        </a>
    <?endif;?>
</div>
