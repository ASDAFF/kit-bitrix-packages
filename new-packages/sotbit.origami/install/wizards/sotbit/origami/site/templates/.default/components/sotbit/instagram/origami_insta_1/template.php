<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
Loc::loadMessages(__FILE__);

$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
global $settings;
?>
<? if (!empty($arResult["MEDIA"]) && is_array($arResult["MEDIA"])):?>
<div class="puzzle_block about__puzzle_block main-container">
    <p class="insta_block-title puzzle_block__title fonts__middle_title">
        <?=$arResult["TITLE"]?>
    </p>
    <div class="insta_block">
        <div class="main_page-catalog_banner_instagram clearfix">
            <div class="insta_block-left_wrapper">
                <div class="top_blocks clearfix">
                    <div class="left-wrapper">
                        <div class="<?=$hoverClass?>">
                            <?
                            if($lazyLoad)
                            {
                                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][0]["IMAGE"].'" class="lazy"';
                            }else{
                                $strLazyLoad = 'src="'.$arResult["MEDIA"][0]["IMAGE"].'"';
                            }
                            ?>
                            <a href="<?=$arResult["MEDIA"][0]["LINK"]?>" target="_blank">
                                <img <?=$strLazyLoad?>>
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            </a>
                        </div>
                        <div class="<?=$hoverClass?>">
                            <?
                            if($lazyLoad)
                            {
                                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][1]["IMAGE"].'" class="lazy"';
                            }else{
                                $strLazyLoad = 'src="'.$arResult["MEDIA"][1]["IMAGE"].'"';
                            }
                            ?>
                            <a href="<?=$arResult["MEDIA"][1]["LINK"]?>" target="_blank">
                                <img <?=$strLazyLoad?>>
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            </a>
                        </div>
                    </div>
                    <div class="right-wrapper <?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][2]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][2]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][2]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                </div>

                <div class="bottom_blocks">
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][3]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][3]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][3]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][4]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][4]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][4]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][5]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][5]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][5]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="insta_block-right_wrapper">

                <div class="top_blocks">
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][6]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][6]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][6]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][7]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][7]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][7]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                    <div class="<?=$hoverClass?>">
                        <?
                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][8]["IMAGE"].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult["MEDIA"][8]["IMAGE"].'"';
                        }
                        ?>
                        <a href="<?=$arResult["MEDIA"][8]["LINK"]?>" target="_blank">
                            <img <?=$strLazyLoad?>>
                            <?if($lazyLoad):?>
                                <span class="loader-lazy"></span>
                            <?endif;?>
                        </a>
                    </div>
                </div>

                <div class="bottom_blocks">
                    <div class="left-wrapper">
                        <div class="origami_instagram-block">
                            <a href="<?=$arResult["SUBSCRIBE"]?>" title="<?=Loc::getMessage("SOTBIT_INSTAGRAM_SUBSCRIBE")?>" class="origami_instagram-logo">
                                <img src="<?=Config::get('LOGO')?>">
                            </a>
                            <div class="origami_instagram-title"><?=$arParams["TITLE_TEXT"]?></div>
                            <div class="origami_instagram-text">
                                <?=$arParams["TEXT"]?>
                            </div>
                            <div class="origami_instagram-subscribe">
                                <a href="<?=$arResult["SUBSCRIBE"]?>" target="_blank" title="<?=Loc::getMessage("SOTBIT_INSTAGRAM_SUBSCRIBE")?>" class="origami_instagram-subscribe__link">
                                    <?=Loc::getMessage("SOTBIT_INSTAGRAM_SUBSCRIBE")?>
                                    <svg class="instagram-logo_icon" width="42" height="16">
                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_arrow"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="right-wrapper">
                        <div class="<?=$hoverClass?>">
                            <?
                            if($lazyLoad)
                            {
                                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][9]["IMAGE"].'" class="lazy"';
                            }else{
                                $strLazyLoad = 'src="'.$arResult["MEDIA"][9]["IMAGE"].'"';
                            }
                            ?>
                            <a href="<?=$arResult["MEDIA"][9]["LINK"]?>" target="_blank">
                                <img <?=$strLazyLoad?>>
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            </a>
                        </div>
                        <div class="<?=$hoverClass?>">
                            <?
                            if($lazyLoad)
                            {
                                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult["MEDIA"][10]["IMAGE"].'" class="lazy"';
                            }else{
                                $strLazyLoad = 'src="'.$arResult["MEDIA"][10]["IMAGE"].'"';
                            }
                            ?>
                            <a href="<?=$arResult["MEDIA"][10]["LINK"]?>" target="_blank">
                                <img <?=$strLazyLoad?>>
                                <?if($lazyLoad):?>
                                    <span class="loader-lazy"></span>
                                <?endif;?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<?endif;?>
