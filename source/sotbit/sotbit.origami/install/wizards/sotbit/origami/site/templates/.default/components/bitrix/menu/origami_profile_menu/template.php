<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>


<?if ($USER->IsAuthorized()):
        $name = trim($USER->GetFullName());
    if (! $name)
        $name = trim($USER->GetLogin());
    if (strlen($name) > 15)
        $name = substr($name, 0, 15).'...';
        ?>
    <div class="header_top_block_cabinet__wrapper">
        <a
                    class="header_top_block_cabinet__title
				fonts__small_text origami_icons_button"
                    href="<?=$arParams['PATH_TO_PROFILE']?>">
                <span class="icon-locked"></span>
                <?=htmlspecialcharsbx($name)?>
        </a>
    <?if(!empty($arResult)):?>
        <ul class="header_top_block_cabinet__list">

           <?
            foreach($arResult as $arItem):?>
            <li>
                    <a href="<?= $arItem["LINK"] ?>" title="<?= $arItem["TEXT"] ?>"><?= $arItem["TEXT"] ?></a>
            </li>
            <?endforeach;?>

            </ul>
    <?endif;?>
        </div>
<?else:?>
    <a class="header_top_block_cabinet__title fonts__small_text"
           href="<?=\Sotbit\Origami\Helper\Config::get('PERSONAL_PAGE')?>">
            <span class="icon-locked"></span>
            <?=GetMessage('ORIGAMI_PERSONAL')?>
    </a>
<?endif?>