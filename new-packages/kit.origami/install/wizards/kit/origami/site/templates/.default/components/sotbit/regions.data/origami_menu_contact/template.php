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

<?if(Config::get("HEADER_CALL") == "Y"):?>
<!-- <a href="javascript:callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>')"> -->
<?endif;?>
<div class="menu-contact">
    <p class="menu-contact__title"><?=Loc::getMessage('CONTACT_TITLE')?></p>
    <div class="menu-contact__item menu-contact__item--contact">
        <?
        if(is_array($arResult['FIELDS']['UF_PHONE']['VALUE'])):
            foreach($arResult['FIELDS']['UF_PHONE']['VALUE'] as $tel):?>
                <a class="menu-contact__item-link menu-contact__item-link--phone main-color-txt" href="tel:<?=$tel?>"><?=$tel?></a>
            <?endforeach;
        else:?>
            <a class="menu-contact__item-link menu-contact__item-link--phone main-color-txt" href="tel:<?=$arResult['FIELDS']['UF_PHONE']['VALUE']?>">
                <?=$arResult['FIELDS']['UF_PHONE']['VALUE']?>
            </a>
        <?endif;?>
    </div>
    <div class="menu-contact__item">
        <p class="menu-contact__item-text">
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
    <div class="menu-contact__item">
        <?
        if(is_array($arResult['FIELDS']['UF_EMAIL']['VALUE'])):
            foreach($arResult['FIELDS']['UF_EMAIL']['VALUE'] as $email):?>
                <a class="menu-contact__item-link main-color-txt" href="mailto:<?=$email?>" title="<?=$email?>">
                    <?=$email?>
                </a>
            <?endforeach;
        else:?>
            <a class="menu-contact__item-link" href="mailto:<?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>" title="<?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>">
                <?=$arResult['FIELDS']['UF_EMAIL']['VALUE']?>
            </a>
        <?endif;?>
    </div>
    <div class="menu-contact__item">
        <p class="menu-contact__item-text"><?=$arResult['FIELDS']['UF_ADDRESS']['VALUE']?></p>
    </div>
    <?if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')):?>
        <a href="javascript:void(0)" class="menu-contact__call-back main-color-btn-fill" onclick="callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>',this)">
            <?=Loc::getMessage('REGIONS_DATA_ORDERCALL_TITLE')?>
        </a>
    <?endif;?>
</div>

