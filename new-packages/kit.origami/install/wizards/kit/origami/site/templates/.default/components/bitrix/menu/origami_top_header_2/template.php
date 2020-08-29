<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<?if(!empty($arResult)):?>
    <?
    foreach($arResult as $i => $item):?>
		<div class="header-two__main-nav-item <?=($item['CHILDREN'])?'header-two__nav-submenu-true':''?> <?if(isset($item["CHILD_SELECTED"]) || $item["SELECTED"]):?>current<?endif;?>">
            <?if($item['LINK'] != $page):?>
	            <a href="<?=$item['LINK']?>" title="<?=$item['TEXT']?>"><?=$item['TEXT']?></a>
            <?else:?>
	            <span><?=$item['TEXT']?></span>
            <?endif;?>
			<?if($item['CHILDREN']):?>
				<ul class="header-two__nav-submenu">
                <?foreach($item['CHILDREN'] as $children):?>
	                <li class="header-two__nav-submenu-item <?=($children['CHILDREN'])?'header-two__nav-submenu-true':''?> <?if(isset($children["CHILD_SELECTED"])):?>current<?endif;?>">
                        <?if($children['LINK'] != $page):?>
	                        <a href="<?=$children['LINK']?>" title="<?=$children['TEXT']?>">
		                        <svg class="site-navigation__item-icon-arrow" width="6" height="12">
			                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_right"></use>
		                        </svg>
                                <?=$children['TEXT']?>
                            </a>
                            <?
                            if($children['CHILDREN']):?>
                                <ul class="header-two__nav-submenu-sublevel">
                                    <?foreach($children['CHILDREN'] as $children2):?>
                                        <li class="header-two__nav-submenu-item">
                                            <?if($children2['LINK'] != $page):?>
                                                <a href="<?=$children2['LINK']?>" title="<?=$children2['TEXT']?>"><?=$children2['TEXT']?></a>
                                            <?else:?>
                                            <span><?=$children2['TEXT']?></span>
                                            <?endif;?>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            <?endif;
                            ?>
                        <?else:?>
	                        <span>
		                        <svg class="site-navigation__item-icon-arrow" width="6" height="12">
			                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_right"></use>
		                        </svg>
                                <?=$children['TEXT']?>
	                        </span>
                        <?endif;?>
	                </li>
				<?endforeach;?>
				</ul>
			<?endif;?>
			<svg class="site-navigation__item-icon" width="14" height="8">
				<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
			</svg>
</div>
	<?endforeach;?>
<?endif?>
