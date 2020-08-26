<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<?if(!empty($arResult)):?>
	<div class="header-two__main-nav-catalog header-two__main-nav-catalog--one <?if($arResult[0]["SELECTED"]):?>active<?endif;?> <?if(isset($arResult[0]["CHILD_SELECTED"])):?>current<?endif;?>">
		<a href="<?=$arResult[0]["LINK"]?>"><?=$arResult[0]["TEXT"]?>
			<svg class="header-two__menu-icon" width="18" height="18">
				<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_menu"></use>
			</svg>
			<svg class="site-navigation__item-icon" width="14" height="8">
				<use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
			</svg>
		</a>
		<div class="header-two__menu-catalog menu-catalog-one">
            <div class="catalog menu-catalog-one main-menu-wrapper">
                <ul class="main-menu-wrapper__submenu-main main-menu-wrapper__submenu-main--main-color"> <!--main-menu-wrapper__submenu-main--gray-->
                    <?foreach($arResult as $item):
                        if($item["DEPTH_LEVEL"] == 0) continue 1;

                        ?>
                    <li class="main-menu_item-main js-main-item <?if($item["SELECTED"]):?>active<?endif;?> <?if(isset($item["CHILD_SELECTED"])):?>current<?endif;?>" data-role="item-menu">
                        <a href="<?=$item['LINK']?>" title="<?=$item['TEXT']?>"><?=$item['TEXT']?>
                            <?if($item["IS_PARENT"]):?>
                            <svg class="menu-catalog-one__menu-item-icon" width="8" height="12">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                            </svg>
                            <?endif;?>
                        </a>
                        <?if($item["IS_PARENT"]):?>
                        <ul class="main-menu-wrapper__submenu js-submenu">
                            <?foreach($item['CHILDREN'] as $children):?>
                            <li class="main-menu_item-submenu js-submenu_item <?if($children["SELECTED"]):?>active<?endif;?> <?if(isset($children["CHILD_SELECTED"])):?>current<?endif;?>" data-role="item-submenu">
                                <a href="<?=$children['LINK']?>" title="<?=$children['TEXT']?>"><?=$children['TEXT']?>
                                    <?if($children["IS_PARENT"]):?>
                                    <svg class="menu-catalog-one__menu-item-icon" width="8" height="12">
                                        <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                                    </svg>
                                    <?endif;?>
                                </a>
                                <?if($children["IS_PARENT"]):?>
                                <ul class="main-menu-wrapper__submenu-two js-submenu">
                                    <?foreach($children['CHILDREN'] as $child):?>
                                    <li class="main-menu_item-submenu js-submenu-two_item <?if($child["SELECTED"]):?>active<?endif;?>" data-role="item-submenu-two">
                                        <a href="<?=$child['LINK']?>" title="<?=$child['TEXT']?>"><?=$child['TEXT']?></a>
                                    </li>
                                    <?endforeach?>
                                </ul>
                                <?endif;?>
                            </li>
                            <?endforeach?>
                        </ul>
                        <?endif;?>
                        <?/*?>
                        <div class="main-menu-wrapper__submenu-banner">
                            <div class="main-menu-wrapper__submenu-banner-img-wrapper">
                                <img src="/local/templates/kit_origami/assets/img/catalog/tovar/4.png" alt="banner">
                            </div>
                            <div class="main-menu-wrapper__submenu-banner-content">
                                <p class="main-menu-wrapper__submenu-banner-title">����������� ��������</p>
                                <div class="main-menu-wrapper__submenu-banner-labels">
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">-10%</a>
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">�����</a>
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">���</a>
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">�������</a>
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">���</a>
                                    <a href="#" class="main-menu-wrapper__submenu-banner-label">�������</a>
                                </div>
                            </div>
                        </div>
                        <?*/?>
                    </li>
                    <?endforeach;?>
                </ul>
                <div class="main-menu-wrapper__submenu-wrapper">
                    <div class="main-menu-wrapper__submenu-two-wrapper"></div>
                    <div class="main-menu-wrapper__submenu-three-wrapper"></div>
                    <div class="main-menu-wrapper__submenu-banner-wrapper"></div>
                </div>
            </div>


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
