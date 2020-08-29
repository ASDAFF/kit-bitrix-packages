<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Kit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<? $idItem = \Bitrix\Main\Security\Random::getString(5);?>

<? if (!empty($arResult["MEDIA"]) && is_array($arResult["MEDIA"])):?>
<div class="puzzle_block main-container">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arResult["TITLE"]?>
        <a href="https://www.instagram.com/<?=$arResult["LOGIN"]?>" target="_blank" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("KIT_INSTAGRAM_GOTO")?>
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div id="social_block_inst_<?=$idItem?>" class="social_block_inst instagram_youTube swiper-container">
        <div class="social_block_inst__wrapper swiper-wrapper">
            <?foreach(array_slice($arResult["MEDIA"], 0, 4) as $key => $media):
                if($lazyLoad)
                {
                    $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$media["IMAGE"].'" class="lazy"';
                }else{
                    $strLazyLoad = 'src="'.$media["IMAGE"].'"';
                }
                ?>
                <div class="social_block_inst__item swiper-slide">
                    <div class="social_block_inst__img">
                        <a href="<?=$media["LINK"]?>" target="_blank" class="social_block_inst__img_link">
                            <? if ($arParams["IMG_DEFAULT"] == 'Y'){?>
                            <picture>
                                <img <?=$strLazyLoad?> alt="<?=$arResult["TITLE"]?>" title="<?=$arResult["TITLE"]?>">
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            </picture>
                            <?} else {?>
                                <img <?=$strLazyLoad?> alt="<?=$arResult["TITLE"]?>" title="<?=$arResult["TITLE"]?>">
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            <?}?>
                        </a>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </div>
</div>
    <script>
        const social_block_inst_<?=$idItem?> = new CreateSlider ({
            sliderContainer: '#social_block_inst_<?=$idItem?>',
            sizeSliderInit: 768
        });
    </script>
<?endif;?>
