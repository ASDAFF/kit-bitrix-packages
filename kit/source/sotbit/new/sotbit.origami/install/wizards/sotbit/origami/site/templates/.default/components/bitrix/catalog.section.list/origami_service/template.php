<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Sotbit\Origami\Helper\Config;
global $sotbitSeoMetaBottomDesc;
global $sotbitSeoMetaTopDesc;
global $sotbitSeoMetaAddDesc;
global $sotbitSeoMetaFile;
global $issetCondition;
global ${$arParams["FILTER_NAME"]};

$this->setFrameMode(true);
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<div class="services-wrapper">

    <?foreach ($arResult['SECTIONS'] as $SECTION):?>
        <div class="service-item" style="background-image: url('<?=CFile::GetPath($SECTION['PICTURE']['ID'])?>')">
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
