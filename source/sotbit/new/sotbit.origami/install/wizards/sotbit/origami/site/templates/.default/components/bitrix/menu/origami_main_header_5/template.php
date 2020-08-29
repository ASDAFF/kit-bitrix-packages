<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
$page = $APPLICATION->GetCurPage(false);
?>

<div class="menu" id="menu-header-three">
    <div class="menu__overlay"></div>
    <div class="menu__wrapper">
        <div class="menu__header">
            <button type="button" class="menu__header-btn" data-entity="close_menu">
                <svg class="menu__header-btn-icon" width="16" height="16">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel"></use>
                </svg>
            </button>
            <div class="menu__header-logo-wrapper">
                <? if($page != '/'):?>
                    <a href="<?=SITE_DIR?>" class="menu__header-logo">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/sotbit_origami/logo.php"
                            )
                        );?>
                    </a>
                <?else:?>
                    <span class="menu__header-logo">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/sotbit_origami/logo.php"
                            )
                        );?>
                    </span>
                <?endif;?>
            </div>
        </div>
        <div class="menu__wrap-scroll">
            <ul class="menu__sectionts sections">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "origami_catalog_sidebar",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "sotbit_left",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "sotbit_left",
                        "USE_EXT" => "Y",
                        'CACHE_SELECTED_ITEMS' => false,
                        "COMPONENT_TEMPLATE" => "origami_catalog_sidebar"
                    ),
                    false
                );
                ?>
                <?foreach ($arResult as $i => $item):?>
                    <li class="section__item">
                        <?$childSelected = false;?>
                        <?if(($item['CHILDREN'])):?>
                            <?foreach ($item['CHILDREN'] as $CHILD):
                                if($CHILD['SELECTED'] == true)
                                    $childSelected = true;
                            endforeach;?>
                        <?endif;?>

                        <a href="<?=($item['CHILDREN']) ? 'javascript:void(0);' : $item['LINK'];?>" class="section__item-main <?if($item["SELECTED"] || $childSelected):?>current<?endif;?>" title="<?=$item['TEXT']?>">  <!-- class "current" adds active state for nav element  -->
                            <?if(isset($item['PARAMS']['ICON'])):?>
                                <div class="section__item-logo">
                                    <svg class="section__item-logo-icon" width="24" height="24">
                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#<?=$item['PARAMS']['ICON'];?>"></use>
                                    </svg>
                                </div>
                            <?endif;?>
                            <p class="section__item-title"><?=$item['TEXT']?></p>
                            <?if($item['CHILDREN']):?>
                                <svg class="section__item-arow-icon" width="8" height="6">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_chevron_down_medium"></use>
                                </svg>
                            <?endif;?>
                        </a>
                        <?if($item['CHILDREN']):?>
                            <ul class="section__item-submenu">
                                <?foreach ($item['CHILDREN'] as $CHILD):?>
                                    <li class="section__item-submenu-item">
                                        <a class="section__item-submenu-item-link <?if($CHILD["SELECTED"]):?>current<?endif;?>" title="<?=$item['TEXT']?>" href="<?=$CHILD['LINK'];?>"><?=$CHILD['TEXT'];?></a>   <!-- class "current" adds active state for nav element  -->
                                    </li>
                                <?endforeach;?>
                            </ul>
                        <?endif;?>
                    </li>
                <?endforeach;?>

            </ul>
    <!--  -->
            <ul class="menu__nav menu-nav">
                <?if(
                    \Bitrix\Main\Loader::includeModule('sotbit.regions') &&
                    \SotbitOrigami::isUseRegions()):?>
                    <li class="menu-nav__item">
                        <a class="menu-nav__item-link select-city__block__text-city__js" href="javascript:void(0);">
                            <div class="menu-nav__item-logo">
                                <svg class="menu-nav__item-logo-icon" width="24" height="24">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location"></use>
                                </svg>
                            </div>
                            <p class="menu-nav__item-title" id="menu-city"></p>
                        </a>
                    </li>
                <?endif;?>
                <li class="menu-nav__item">
                    <a class="menu-nav__item-link" href="<?=\Sotbit\Origami\Helper\Config::get('PERSONAL_PAGE')?>" title="<?=Loc::getMessage("SOTBIT_PERSONAL_CABINET")?>">
                        <div class="menu-nav__item-logo">
                            <svg class="menu-nav__item-logo-icon" width="24" height="24">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_login"></use>
                            </svg>
                        </div>
                        <p class="menu-nav__item-title"><?=Loc::getMessage("SOTBIT_PERSONAL_CABINET")?></p>
                    </a>
                </li>
                <li class="menu-nav__item">
                    <a class="menu-nav__item-link" href="<?=\Sotbit\Origami\Helper\Config::get('BASKET_PAGE')?>" title="<?=Loc::getMessage("SOTBIT_PERSONAL_BASKET_TITLE")?>">
                        <div class="menu-nav__item-logo">
                            <svg class="menu-nav__item-logo-icon" width="24" height="24">
                                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cart"></use>
                            </svg>
                        </div>
                        <p class="menu-nav__item-title"><?=Loc::getMessage("SOTBIT_PERSONAL_BASKET_TITLE")?></p>
                        <span class="menu-nav__item-count" id="menu-basket-count">0</span>
                    </a>
                </li>
                <?if(in_array('DELAY', unserialize(\Sotbit\Origami\Helper\Config::get('ACTION_PRODUCTS')))):?>
                    <li class="menu-nav__item">
                        <a class="menu-nav__item-link" href="<?=\Sotbit\Origami\Helper\Config::get('BASKET_PAGE').'#favorit'?>" title="<?=Loc::getMessage("SOTBIT_PERSONAL_FAVORITE_PRODUCTS")?>">
                            <div class="menu-nav__item-logo">
                                <svg class="menu-nav__item-logo-icon" width="24" height="24">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_favourite"></use>
                                </svg>
                            </div>
                            <p class="menu-nav__item-title"><?=Loc::getMessage("SOTBIT_PERSONAL_FAVORITE_PRODUCTS")?></p>
                            <span class="menu-nav__item-count" id="menu-favorites-count">0</span>
                        </a>
                    </li>
                <?endif;?>
                <?if(in_array('COMPARE', unserialize(\Sotbit\Origami\Helper\Config::get('ACTION_PRODUCTS')))):?>
                    <li class="menu-nav__item">
                        <a class="menu-nav__item-link" href="<?=\Sotbit\Origami\Helper\Config::get('COMPARE_PAGE')?>" onclick="compareLinkAccess(event);" title="<?=Loc::getMessage("SOTBIT_PERSONAL_COMPARE_PRODUCTS")?>">
                            <div class="menu-nav__item-logo">
                                <svg class="menu-nav__item-logo-icon" width="24" height="24">
                                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_compare"></use>
                                </svg>
                            </div>
                            <p class="menu-nav__item-title"><?=Loc::getMessage("SOTBIT_PERSONAL_COMPARE_PRODUCTS")?></p>
                            <span class="menu-nav__item-count" id="menu-compare-count">0</span>
                        </a>
                    </li>
                <?endif;?>
            </ul>

            <script>
                function compareLinkAccess(e) {
                    if (parseInt(document.querySelector('#menu-compare-count').textContent) <= 0) {
                        e.preventDefault();
                    }
                }
            </script>

            <div class="menu__contact">
                <?
                if(
                    \Bitrix\Main\Loader::includeModule('sotbit.regions') &&
                    \SotbitOrigami::isUseRegions() &&
                    is_dir($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/sotbit/regions.data')
                ):
                    $APPLICATION->IncludeComponent(
                        "sotbit:regions.data",
                        "origami_menu_contact",
                        [
                            "CACHE_TIME"    => "36000000",
                            "CACHE_TYPE"    => "A",
                            "REGION_FIELDS" => ['UF_ADDRESS','UF_PHONE','UF_WORKTIME','UF_EMAIL'],
                            "REGION_ID"     => $_SESSION['SOTBIT_REGIONS']['ID']
                        ]
                    );
                else:?>
                    <div class="menu-contact">
                        <p class="menu-contact__title"><?=Loc::getMessage('CONTACT_TITLE')?></p>
                        <div class="menu-contact__item menu-contact__item--contact">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/sotbit_origami/contacts_phone.php")
                            );
                            ?>


                        </div>
                        <div class="menu-contact__item">
                            <p class="menu-contact__item-text">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."include/sotbit_origami/contacts_worktime.php")
                                );
                                ?>
                            </p>
                        </div>
                        <div class="menu-contact__item">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/sotbit_origami/contacts_email.php")
                            );
                            ?>
                        </div>
                        <div class="menu-contact__item">
                            <p class="menu-contact__item-text">
                                <?
                                $APPLICATION->IncludeComponent("bitrix:main.include", "", [
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH"           =>
                                        SITE_DIR."include/sotbit_origami/contacts_address.php",
                                ]);
                                ?>
                            </p>
                        </div>
                        <?if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('sotbit.orderphone')):?>
                            <a href="javascript:void(0)" class="menu-contact__call-back main-color-btn-fill" onclick="callbackPhone('<?=SITE_DIR?>','<?=SITE_ID?>',this)">
                                <?=Loc::getMessage('REGIONS_DATA_ORDERCALL_TITLE')?>
                            </a>
                        <?endif;?>
                    </div>
                <?endif;?>
            </div>
        </div>
    </div>
</div>
