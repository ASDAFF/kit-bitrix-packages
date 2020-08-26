<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
?>

<div class="small-product-block">
    <a onclick="" class="small-product-block__img-link" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$item['NAME']?>">
        <?if(!empty($item['PICT'])):?>
            <img
	            class="small-product-block__img <?if(arResult['LAZY_LOAD']):?>lazy<?endif;?>"
	            <?=$strLazyLoad?>
	            alt="<?=$imgAlt?>"
	            title="<?=$imgTitle?>"
                width="<?=$item['PICT']['WIDTH'] > 0 ? $item['PICT']['WIDTH'] : ''?>"
                height="<?=$item['PICT']['HEIGHT'] > 0 ? $item['PICT']['HEIGHT'] : ''?>"
            >
            <?if($arResult['LAZY_LOAD']):?>
                <span class="loader-lazy loader-lazy--small"></span> <!--LOADER_LAZY-->
            <?endif;?>
        <?endif;?>
    </a>
    <div class="small-product-block__info">
        <a onclick="" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>"
           class="small-product-block__name-product"><?=$productTitle?></a>
	    <?
	    if($item['SECTION']['ACTIVE'] == 'Y'){
	    	?>
		    <a onclick="" href="<?=$item['SECTION']['SECTION_PAGE_URL']?>"
		       class="small-product-block__name-section" title="<?=$item['SECTION']['NAME']?>">
                <?=$item['SECTION']['NAME']?>
		    </a>
		    <?
	    }
	    else{
            ?>
		    <span
		       class="small-product-block__name-section" title="<?=$item['SECTION']['NAME']?>">
                <?=$item['SECTION']['NAME']?>
		    </span>
            <?
	    }
        $frame = $this->createFrame()->begin();?>
        <div class="small-product-block__price-block">
            <div class="small-product-block__price-main">
                <?=$price['PRINT_PRICE']?>
            </div>
            <?if($price['DISCOUNT'] > 0):?>
	            <div class="small-product-block__price-old">
                    <?=$price['PRINT_BASE_PRICE']?>
	            </div>
	        <?endif;?>
        </div>
        <?if($price['DISCOUNT'] > 0):?>
        <div class="small-product-block__saving">
            <span class="small-product-block__saving-title">
                <?=Loc::getMessage('SAVE_PRICE')?>:
            </span>
            <div class="small-product-block__saving-volume">
                <?=$price['PRINT_DISCOUNT']?>
            </div>
        </div>
        <?endif;
        $frame->end();
        ?>
    </div>
</div>

