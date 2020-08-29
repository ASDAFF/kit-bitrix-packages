<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

global $kitSeoMetaBottomDesc;
global $kitSeoMetaTopDesc;
global $kitSeoMetaAddDesc;
global $kitSeoMetaFile;
global $issetCondition;
global ${$arParams["FILTER_NAME"]};

$this->setFrameMode(true);
use Sotbit\Origami\Helper\Config;
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<?if (count($arResult['SECTIONS']) > 0):?>
<div class="catalog_content__category_block">
	<div class="catalog_content__category">
        <?
        foreach ($arResult['SECTIONS'] as $section)
        {
            $this->AddEditAction($section['ID'], $section['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($section['ID'], $section['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

            if($lazyLoad)
            {
                $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$section['PICTURE']['SRC'].'"';
                $lazyClass = 'lazy';
            }else{
                $strLazyLoad = 'src="'.$section['PICTURE']['SRC'].'"';
                $lazyClass = '';
            }
            ?>
			<a href="<?=$section['SECTION_PAGE_URL'] ?>" title="<?=$section['NAME']?>" class="catalog_content__category_item <?=$hoverClass?>">
				<div class="catalog_content__category_block_img">
					<img class="catalog_content__category_img <?=$lazyClass?>"
                         <?=$strLazyLoad?>
                         alt="<?=$section['PICTURE']['ALT']?>"
                         title="<?=$section['PICTURE']['TITLE']?>"
                    >
                    <?if($lazyLoad):?>
                    <span class="loader-lazy"></span>
                    <?endif;?>
				</div>
				<p class="catalog_content__category_img_title fonts__middle_text"><?=$section['NAME']?></p>
			</a>
            <?
        }
        ?>
	</div>
    <? if (count($arResult['SECTIONS']) > 5)
    {
        ?>
		<div id="loadMore"><?= GetMessage('SEE_ALL_SECTIONS') ?> <i class="icon-nav_button"></i></div>
        <?
    }
    ?>
</div>
<script>galleryMoreItemsResize()</script>
<?endif;?>
