<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$cartStyle = 'bx-basket';
$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;

if ($arParams['POSITION_FIXED'] == 'Y')
{
    $cartStyle .= "-fixed {$arParams['POSITION_HORIZONTAL']} {$arParams['POSITION_VERTICAL']}";
    if ($arParams['SHOW_PRODUCTS'] == 'Y')
        $cartStyle .= ' bx-closed';
}
else
{
    $cartStyle .= ' bx-opener';
}

$orderAjaxInclude = false;
if ($_SERVER['REQUEST_URI'] == \Sotbit\Origami\Helper\Config::get('ORDER_PAGE'))
    $orderAjaxInclude = true;

?>

<div class="side-panel-over"></div>
<div class="side-panel">
    <div class="side-panel__nav">
        <a href="<?=($arParams['PATH_TO_BASKET']) ? $arParams['PATH_TO_BASKET'] : '#';?>" class="side-panel-main__nav-item side-panel-main__nav-item--cart">
            <svg width="21" height="21">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cart"></use>
            </svg>
            <span class="side-panel-main__nav-count"><?=($arResult['NUM_PRODUCTS']) ? $arResult['NUM_PRODUCTS'] : 0;?></span>
        </a>
        <a href="#" class="side-panel-main__nav-item side-panel-main__nav-item--delay">
            <svg width="21" height="21">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_favourite"></use>
            </svg>
            <span class="side-panel-main__nav-count"><?=($arResult['NUM_PRODUCTS_DELAY']) ? $arResult['NUM_PRODUCTS_DELAY'] : 0;?></span>
        </a>
        <a href="<?=\Sotbit\Origami\Helper\Config::get('COMPARE_PAGE')?>" <?if($arResult['NUM_PRODUCTS_COMPARE'] == 0): ?> onclick="return false;" <?endif;?> class="side-panel-main__nav-item side-panel-main__nav-item--compare">
            <svg width="21" height="21">
                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_compare"></use>
            </svg>
            <span class="side-panel-main__nav-count"><?=($arResult['NUM_PRODUCTS_COMPARE']) ? $arResult['NUM_PRODUCTS_COMPARE'] : 0;?></span>
        </a>
        <?if (!$USER->IsAuthorized()):?>
            <a href="/personal/" class="side-panel-main__nav-item side-panel-main__nav-item--login" id="right_auth"
               onclick="rightPanel.auth.showAuth('/auth/')" rel="nofollow">
                <svg width="18" height="20">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_login"></use>
                </svg>
            </a>
        <?else:?>
            <a href="/personal/" class="side-panel-main__nav-item side-panel-main__nav-item--auth" id="right_auth">
                <svg width="18" height="20">
                    <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_authorized_medium"></use>
                </svg>
            </a>
        <?endif;?>
    </div>
    <div class="side-panel__main">
        <div class="side-panel__main-item side-panel__main-auth" id="sidePanel-auth"></div>
        <div class="side-panel__main-item side-panel__main-basket <?if($orderAjaxInclude):?>without-order<?endif;?>" id="sidePanel-basket"></div>
        <div class="side-panel__main-item side-panel__main-order main-order" id="sidePanel-order">
            <?if(!$orderAjaxInclude):?>
                <div class="side-panel__main-header">
                    <p class="side-panel__main-title">
                        <?=GetMessage('ORDER_TITLE')?>
                    </p>
                </div>
                <div class="main-order__content">
                    <?
                    $APPLICATION->IncludeComponent(
                        'bitrix:sale.order.ajax',
                        'kit_order_right_panel',
                        array(
                            'DISABLE_BASKET_REDIRECT' => 'Y'
                        )
                    );
                    ?>
                </div>
            <?endif;?>
        </div>
    </div>
</div>
<script>
    let sidePanelMainAuth = document.querySelector('.side-panel__main-auth');
        if (sidePanelMainAuth) {
            new PerfectScrollbar(sidePanelMainAuth,{
                wheelSpeed: 0.5,
                wheelPropagation: true,
                minScrollbarLength: 20
            });
        }

</script>
<script>
    var <?=$cartId?> = new BitrixSmallCart;
</script>
<?
$props = ($arResult['PROPS'])?array_keys(reset($arResult['PROPS'])):[];
?>

<script>
    $(document).on('click', "#order_oc_top", function () {
        let siteId = "<?=SITE_ID?>";
        let siteDir = "<?=SITE_DIR?>";
        let props = "<?=base64_encode(serialize($props))?>";
        $.ajax({
            url: siteDir + 'include/ajax/oc.php',
            type: 'POST',
            data:{'site_id':siteId,'basketData':props},
            success: function(html)
            {
                window.rightPanel.closePanel();
                showModal(html);

            }
        });
    });


</script>



