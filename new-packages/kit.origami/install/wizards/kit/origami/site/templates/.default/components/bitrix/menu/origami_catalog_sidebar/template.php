<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<?if(!empty($arResult)):?>
        <li class="section__item">
            <a href="javascript:void(0);" class="section__item-main <?if(isset($arResult[0]["CHILD_SELECTED"]) || $arResult[0]['SELECTED']):?>current<?endif;?>" title="<?=$arResult[0]['TEXT']?>">  <!-- class "current" adds active state for nav element  -->
                <?if(isset($arResult[0]['PARAMS']['ICON'])):?>
                    <div class="section__item-logo">
                        <svg class="section__item-logo-icon" width="24" height="24">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#<?=$arResult[0]['PARAMS']['ICON'];?>"></use>
                        </svg>
                    </div>
                <?else:?>
                    <div class="section__item-logo">
                        <svg class="section__item-logo-icon" width="24" height="24">
                            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mobile_menu_bottom_catalog"></use>
                        </svg>
                    </div>
                <?endif;?>
                <p class="section__item-title"><?=$arResult[0]['TEXT']?></p>
                <svg class="section__item-arow-icon" width="8" height="6">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_chevron_down_medium"></use>
                </svg>
            </a>
                <ul class="section__item-submenu">
                    <?foreach ($arResult as $i => $CHILD):?>
                        <?if($i != 0):?>
                            <li class="section__item-submenu-item">
                                <a class="section__item-submenu-item-link <?if($CHILD["SELECTED"]):?>current<?endif;?>" href="<?=$CHILD['LINK'];?>" title="<?=$CHILD['TEXT'];?>"><?=$CHILD['TEXT'];?></a>   <!-- class "current" adds active state for nav element  -->
                            </li>
                        <?endif;?>
                    <?endforeach;?>
                </ul>

        </li>
<?endif;?>


