<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);
Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<?if(!empty($arResult)):?>
	<div class="header-two__main-nav-catalog <?if($arResult[0]["SELECTED"]):?>active<?endif;?> <?if(isset($arResult[0]["CHILD_SELECTED"])):?>current<?endif;?>" aria-haspopup="true">
		<a href="<?=$arResult[0]["LINK"]?>"><?=$arResult[0]["TEXT"]?>
			<svg class="header-two__menu-icon" width="18" height="18">
				<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_menu"></use>
			</svg>
			<svg class="site-navigation__item-icon" width="14" height="8">
				<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
			</svg>
		</a>
		<div class="header-two__menu-catalog menu-catalog">
            <?foreach($arResult as $item):
                if($item["DEPTH_LEVEL"] == 0) continue 1;

                ?>
	            <div class="menu-catalog__item">
                    <?if($item['PICTURE']):?>
		            <div class="menu-catalog__image">
			            <img src="<?=$item['PICTURE']['src']?>" width="<?=$item['PICTURE']['width']?>" height="<?=$item['PICTURE']['height']?>" alt="<?=$item['TEXT']?>" title="<?=$item['TEXT']?>">
		            </div>
                    <?endif;?>
		            <div class="menu-catalog__description <?if($item["SELECTED"]):?>active<?endif;?> <?if(isset($item["CHILD_SELECTED"])):?>current<?endif;?>">
                        <?if($item['LINK'] != $page):?>
	                        <a href="<?=$item['LINK']?>" title="<?=$item['TEXT']?>" class="menu-catalog__title"><?=$item['TEXT']?></a>
                        <?else:?>
				            <span class="menu-catalog__title"><?=$item['TEXT']?></span>
                        <?endif;?>

			            <?if($item['CHILDREN']):?>
			                <?foreach($item['CHILDREN'] as $children):?>
					            <div class="menu-catalog__section <?if($children["SELECTED"]):?>active<?endif;?> <?if(isset($children["CHILD_SELECTED"])):?>current<?endif;?>">
                                    <?if($children['LINK'] != $page):?>
	                                    <a href="<?=$children['LINK']?>" title="<?=$children['TEXT']?>" class="menu-catalog__section-title"><?=$children['TEXT']?></a>
                                    <?else:?>
							            <span class="menu-catalog__section-title"><?=$children['TEXT']?></span>
                                    <?endif;?>
					                <?if($children['CHILDREN']):?>
							            <ul class="menu-catalog__section-list">
		                                    <?foreach($children['CHILDREN'] as $child):?>
									            <li class="menu-catalog__section-item <?if($child["SELECTED"]):?>active<?endif;?>">
										            <?if($child['LINK'] != $page):?>
										                <a href="<?=$child['LINK']?>" title="<?=$child['TEXT']?>"><?=$child['TEXT']?></a>
													<?else:?>
											            <span><?=$child['TEXT']?></span>
										            <?endif;?>
									            </li>
		                                    <?endforeach;?>
							            </ul>
	                                <?endif;?>
					            </div>
							<?endforeach;?>
						<?endif;?>
		            </div>
	            </div>
            <?endforeach;?>
		</div>
</div>
<?endif;?>

<script>
    jQuery(document).ready(function( $ ) {

        $("#menu").mmenu({
            "extensions": [
                "pagedim-black"
            ]
        });

    });

</script>
