<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<? if (!empty($arResult["MEDIA"]) && is_array($arResult["MEDIA"])):?>
<div class="puzzle_block">
    <p class="puzzle_block__title fonts__middle_title">
        <?=$arResult["TITLE"]?>
        <a href="https://www.instagram.com/<?=$arResult["LOGIN"]?>" target="_blank" class="puzzle_block__link fonts__small_text">
            <?=Loc::getMessage("SOTBIT_INSTAGRAM_GOTO")?>
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div class="social_block instagram_youTube">
        <div class="social_block__wrapper">
            <?foreach(array_slice($arResult["MEDIA"], 0, 4) as $key => $media):?>
                <div class="social_block__item">
                    <div class="social_block__img">
                        <a href="<?=$media["LINK"]?>" target="_blank" class="social_block__img_link">
                            <? if ($arParams["IMG_DEFAULT"] == 'Y'){?>
                            <picture>
                                <source srcset="/upload/sotbit.instagram/370/<?=$key ?>/<?=$arResult["LOGIN"]?>.jpg" media="(min-width: 576px)">
                                <source srcset="/upload/sotbit.instagram/540/<?=$key ?>/<?=$arResult["LOGIN"]?>.jpg" media="(max-width: 575px)">
                                <img src="<?=$media["IMAGE"]?>" alt="<?=$arResult["TITLE"]?>" title="<?=$arResult["TITLE"]?>">
                            </picture>
                            <?} else {?>
                                <img src="<?=$media["IMAGE"]?>" alt="<?=$arResult["TITLE"]?>" title="<?=$arResult["TITLE"]?>">
                            <?}?>

                        </a>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </div>
</div>
<?endif;?>
