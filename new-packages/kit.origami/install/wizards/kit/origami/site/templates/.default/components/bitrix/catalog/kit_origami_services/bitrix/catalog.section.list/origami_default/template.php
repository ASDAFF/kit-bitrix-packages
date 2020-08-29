<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Sotbit\Origami\Helper\Config;
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
        <div class="service-item" <?if(isset($SECTION['DETAIL_PICTURE'])):?> style="background-image: url('<?=CFile::GetPath($SECTION['DETAIL_PICTURE'])?>')" <?endif?>>
            <div class="service-item__content">
                <a class="service-item__title" href="<?=$SECTION['SECTION_PAGE_URL']?>">
                    <span><?=$SECTION['NAME'];?></span>
                </a>
                <?if(isset($SECTION['CHILDREN'])):?>
                    <div class="service-item__links-wrapper">
                        <?foreach ($SECTION['CHILDREN'] as $CHILD):?>
                            <a class="service-item__link main-color-txt-hov" href="<?=$CHILD['SECTION_PAGE_URL']?>">
                                <span><?=$CHILD['NAME']?></span>
                            </a>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>
        </div>
    <?endforeach;?>

</div>
