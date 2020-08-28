<?
use Sotbit\Origami\Helper\Config;

if (! defined( 'B_PROLOG_INCLUDED' ) || B_PROLOG_INCLUDED !== true) die();

$this->IncludeLangFile( 'template.php' );
$cartId = $arParams['cartId'];

switch (\Sotbit\Origami\Helper\Config::get('HEADER'))
{
    case 1:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_1.php');
        break;
    case 2:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_2.php');
        break;
    default:
        require (realpath( dirname( __FILE__ ) ) . '/top_template_2.php');
        break;
}

//if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult["NUM_PRODUCTS_ALL"])
//{
    ?>

    <script>
        ////not work in ready
        //$('#order_oc_top').on('click',function(){
        //    let siteId = $(this).data('site_id');
        //    let siteDir = $(this).data('site_dir');
        //    let props = $(this).data('props');
        //    $.ajax({
        //        url: siteDir + 'include/ajax/oc.php',
        //        type: 'POST',
        //        data:{'site_id':siteId,'basketData':props},
        //        success: function(html)
        //        {
        //            showModal(html);
        //        }
        //    });
        //});
        //
        <?if(isset($arResult["arBasketID"])):?>
        arBasketID = <?=json_encode($arResult["arBasketID"])?>;
        <?else:?>
        arBasketID = [];
        <?endif;?>
        <?if(isset($arResult["arDelayID"])):?>
        arDelayID = <?=json_encode($arResult["arDelayID"])?>;
        <?else:?>
        arDelayID = [];
        <?endif;?>
        <?if(isset($arResult["arCompareID"])):?>
        arCompareID = <?=json_encode($arResult["arCompareID"])?>;
        <?else:?>
        arCompareID = [];
        <?endif;?>
        BX.onCustomEvent('OnBasketChangeAfter');
    </script>

    <?
//}
require(realpath(dirname(__FILE__)).'/template_mobile_top.php');
