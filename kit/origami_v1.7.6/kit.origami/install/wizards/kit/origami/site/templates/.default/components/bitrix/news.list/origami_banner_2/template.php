<?
use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin();

$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<div class="puzzle_block block_main_canvas_all_width">
    <div class="block_main_canvas__big_canvas block_main_canvas__big_canvas--two slider-canvas owl-carousel">
        <?php
        foreach($arResult['BIG_CANVAS'] as $item)
        {
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            if($lazyLoad)
            {
                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$item["DETAIL_PICTURE"]["SRC"].'"';
                $lazyClass = ' lazy';
            }else{
                $strLazyLoad = 'src="'.$item["DETAIL_PICTURE"]["SRC"].'"';
                $lazyClass = "";
            }

            ?>
            <div class="" id="<?=$this->GetEditAreaID($item['ID'])?>">
                <div class="block_main_canvas_all_width__img_wrapper">
                    <img class="block_main_canvas_all_width__img<?=$lazyClass?>"
                        <?=$strLazyLoad?>
                         width="<?=$item["DETAIL_PICTURE"]['WIDTH']?>"
                         height="<?=$item["DETAIL_PICTURE"]['HEIGHT']?>"
                         title="<?=$item["DETAIL_PICTURE"]['TITLE']?>"
                         alt="<?=$item["DETAIL_PICTURE"]['ALT']?>"
                    >
                    <?if($lazyLoad):?>
                        <span class="loader-lazy loader-lazy--big"></span>
                    <?endif;?>
                </div>
                <div class="block_main_canvas_all_width__info">
                    <div class="block_main_canvas__big_canvas__title fonts__main_title">
                        <?=$item['NAME']?></div>
                    <div class="block_main_canvas__big_canvas__comment fonts__small_text">
                        <?=$item['PREVIEW_TEXT']?></div>
                    <?php
                    if($item['PROPERTIES']['URL']['VALUE'])
                    {
                        ?>
                        <a <?=($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y')?'target="_blank"':''?>
                                class="main_btn main_btn--inline button_another sweep-to-right"
                                href="<?=$item['PROPERTIES']['URL']['VALUE']?>">
                            <?=($item['PROPERTIES']['BUTTON_TEXT']['VALUE'])
	                            ?$item['PROPERTIES']['BUTTON_TEXT']['VALUE']
	                            :Loc::getMessage('BANNER_FULL_DEFAULT_BTN')?>
                        </a>
                        <?
                    }
                    ?>
                </div>
                <?
                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$item["PREVIEW_PICTURE"]["SRC"].'" class="lazy"';
                }else{
                    $strLazyLoad = 'src="'.$item["PREVIEW_PICTURE"]["SRC"].'"';
                }
                ?>
                <div class="block_main_canvas__big_canvas__dop_photo">
                    <img  <?=$strLazyLoad?>
                            width="<?=$item["PREVIEW_PICTURE"]['WIDTH']?>"
                            height="<?=$item["PREVIEW_PICTURE"]['HEIGHT']?>"
                            title="<?=$item["PREVIEW_PICTURE"]['TITLE']?>"
                            alt="<?=$item["PREVIEW_PICTURE"]['ALT']?>"
                    >
                    <?if($lazyLoad):?>
                        <span class="loader-lazy loader-lazy--big"></span>
                    <?endif;?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
	$('.slider-canvas').owlCarousel({
		stopOnHover: true,
		loop:true,
		items:1,
		nav:true,
		navText : ["",""],
		autoplay:false,
		smartSpeed:500,
	});
</script>
