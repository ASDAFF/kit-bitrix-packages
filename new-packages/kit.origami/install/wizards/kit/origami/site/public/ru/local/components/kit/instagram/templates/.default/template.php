<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div>
    <div class="instagram_title">
        <?=$arResult["TITLE"]?>
        <a href="https://www.instagram.com/<?=$arResult["LOGIN"]?>" target="_blank"><?=Loc::getMessage("KIT_INSTAGRAM_GOTO")?></a>
    </div>
    <div class="instagram_wrapper">
        <?foreach($arResult["MEDIA"] as $media):?>
            <div class="instagram_item">
                <a href="<?=$media["LINK"]?>" target="_blank">
                    <img src="<?=$media["IMAGE"]?>" alt="">
                </a>
            </div>
        <?endforeach?>
    </div>
</div>
