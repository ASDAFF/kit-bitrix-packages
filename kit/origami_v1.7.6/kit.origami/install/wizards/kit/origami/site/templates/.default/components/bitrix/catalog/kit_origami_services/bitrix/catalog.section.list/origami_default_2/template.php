<?php
/**
 * Copyright (c) 25/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Kit\Origami\Helper\Config;
global $kitSeoMetaBottomDesc;
global $kitSeoMetaTopDesc;
global $kitSeoMetaAddDesc;
global $kitSeoMetaFile;
global $issetCondition;
global ${$arParams["FILTER_NAME"]};

$this->setFrameMode(true);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>
<div class="services-wrapper">

    <?foreach ($arResult['SECTIONS'] as $SECTION):?>
    <div class="service-item__wrapper">
        <div class="service-item" <?if(isset($SECTION['DETAIL_PICTURE'])):?> style="background-image: url('<?=CFile::GetPath($SECTION['DETAIL_PICTURE'])?>')" <?endif;?> >
            <div class="service-item__content-wrapper">
                <div class="service-item__content">
                    <a class="service-item__title" href="<?=$SECTION['SECTION_PAGE_URL']?>">
                        <span><?=$SECTION['NAME'];?></span>
                    </a>
                    <div class="service-item__links-resizer">
                        <div>
                            <div class="service-item__links-wrapper">
                                <?if(isset($SECTION['CHILDREN'])):?>
                                    <?foreach ($SECTION['CHILDREN'] as $CHILD):?>
                                        <a href="<?=$CHILD['SECTION_PAGE_URL']?>" class="service-item__link main-color-txt-hov">
                                            <span><?=$CHILD['NAME']?></span>
                                        </a>
                                    <?endforeach;?>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?endforeach;?>

</div>
