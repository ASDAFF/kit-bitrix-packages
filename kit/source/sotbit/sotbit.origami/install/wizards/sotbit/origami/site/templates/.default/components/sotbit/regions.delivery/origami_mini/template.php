<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
Loc::loadMessages(__FILE__);
?>
<?if($arResult["DELIVERY"]):?>
    <?foreach($arResult["DELIVERY"] as $arDelivery):?>
    <div class="product_detail_info__delivery-item">
        <p class="product_detail_info__delivery-way"><?=$arDelivery["NAME"]?></p>
        <p class="product_detail_info__delivery-time"><?if($arDelivery["TIME"]):?><?=$arDelivery["TIME"]?> &mdash; <?endif;?><span class="product_detail_info__delivery-price"><?=$arDelivery["PRINT_PRICE"]?></span></p>
    </div>
    <?endforeach;?>
    <div class="product_detail_info__delivery-more">
        <?if($arParams["SHOW_DELIVERY_TAB"] == 'Y'):?>
        <a class="product_detail_info__delivery-link product_detail_info__delivery-link--btn" href="#TAB_DELIVERY"><?=Loc::getMessage('sotbit.regions_SHOW_DELIVERY_TAB')?>
            <svg class="product_detail_info__delivery-link-icon" width="10" height="6">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_small"></use>
            </svg>
        </a>
        <?endif;?>
        <?if($arParams["SHOW_DELIVERY_PAGE"] == 'Y'):?>
        <a class="product_detail_info__delivery-link" title="<?=Loc::getMessage('sotbit.regions_DETAIL_DELIVERY_PAGE')?>" target="_blank" href="<?=SITE_DIR?>help/delivery/"><?=Loc::getMessage('sotbit.regions_DETAIL_DELIVERY_PAGE')?></a>
        <?endif;?>
    </div>
<?endif;?>
<script>
    if(!RegionsDeliveryMini) {
        var RegionsDeliveryMini = new SotbitRegionsDeliveryMini({
            'componentPath': '<?=CUtil::JSEscape($this->__component->__path)?>',
            'parameters': '<?=CUtil::JSEscape(base64_encode(serialize($arParams)))?>',
            'siteId': '<?=CUtil::JSEscape(SITE_ID)?>',
            'template': '<?=CUtil::JSEscape($this->__name)?>',
            'ajax': '<?=($arParams["AJAX"] == 'Y')?>',
        });
    }
</script>
