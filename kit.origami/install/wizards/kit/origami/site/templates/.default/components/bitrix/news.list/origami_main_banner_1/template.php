<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use Kit\Origami\Helper\Config;
$hoverClass = implode(" ", Config::getArray("HOVER_EFFECT"));
$lazyLoad = (Config::get('LAZY_LOAD') == "Y");
?>

<div class="puzzle_block main-banner-two">
    <div class="block_main_canvas variant_two">
        <?php
        if ($arResult['SIDE'])
        {
            ?>
            <div class="block_main_canvas_item">
                <div class="block_main_canvas__small_canvas_block">
                    <?
                    for ($i = 0; $i < 2; ++$i)
                    {
                        $this->AddEditAction($arResult['SIDE'][$i]['ID'], $arResult['SIDE'][$i]['EDIT_LINK'], CIBlock::GetArrayByID($arResult['SIDE'][$i]["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arResult['SIDE'][$i]['ID'], $arResult['SIDE'][$i]['DELETE_LINK'], CIBlock::GetArrayByID($arResult['SIDE'][$i]["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult['SIDE'][$i]['PREVIEW_PICTURE']['SRC'].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult['SIDE'][$i]['PREVIEW_PICTURE']['SRC'].'"';
                        }

                        ?>
                        <div class="block_main_canvas__small_canvas"
                             id="<?= $this->GetEditAreaID($arResult['SIDE'][$i]['ID']) ?>">
                            <a
                                <?= ($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y') ? 'target="_blank"' : '' ?>
                                    class="block_main_canvas__small_canvas_link <?=$hoverClass?>"
                                    href="<?= $arResult['SIDE'][$i]['PROPERTIES']['URL']['VALUE'] ?>" title="<?=$arResult['SIDE'][$i]['NAME']?>">
                                <div class="block_main_canvas__small_canvas_img">
                                    <div class="block_main_canvas__small_canvas_title fonts__small_title">
                                        <?= $arResult['SIDE'][$i]['NAME'] ?>
                                    </div>
                                    <img <?=$strLazyLoad?>
                                         width="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['WIDTH']?>"
                                         height="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['HEIGHT']?>"
                                         title="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['TITLE']?>"
                                         alt="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['ALT']?>"
                                    >
                                    <?if($lazyLoad):?>
                                        <span class="loader-lazy"></span>
                                    <?endif;?>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        if ($arResult['MAIN']) {
            ?>
            <div class="block_main_canvas_item">
                <div class="block_main_canvas__big_canvas slider-canvas owl-carousel">
                    <?php
                    foreach ($arResult['MAIN'] as $item) {
                        $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$item['DETAIL_PICTURE']['SRC'].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$item['DETAIL_PICTURE']['SRC'].'"';
                        }

                        ?>
                        <div class="block_main_canvas__big_canvas__content"
                             id="<?= $this->GetEditAreaID($item['ID']) ?>">
                            <img <?=$strLazyLoad?>
                                 width="<?=$item['DETAIL_PICTURE']['WIDTH']?>"
                                 height="<?=$item['DETAIL_PICTURE']['HEIGHT']?>"
                                 title="<?=$item['DETAIL_PICTURE']['TITLE']?>"
                                 alt="<?=$item['DETAIL_PICTURE']['ALT']?>"
                            >
                            <?if($lazyLoad):?>
                                <span class="loader-lazy loader-lazy--big"></span>
                            <?endif;?>
                            <div class="block_main_canvas__big_canvas__info">
                                <div class="block_main_canvas__big_canvas__title fonts__middle_title">
                                    <?= $item['NAME'] ?></div>
                                <div class="block_main_canvas__big_canvas__comment fonts__small_text">
                                    <?= $item['PREVIEW_TEXT'] ?></div>
                                <?php
                                if ($item['PROPERTIES']['URL']['VALUE']) {
                                    ?>
                                    <a <?= ($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y') ? 'target="_blank"' : '' ?>
                                            class="main_btn button_another sweep-to-right"
                                            href="<?= $item['PROPERTIES']['URL']['VALUE'] ?>" title="<?=$item['NAME']?>">
                                        <?= $item['PROPERTIES']['BUTTON_TEXT']['VALUE'] ?>
                                    </a>
                                    <?
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        if ($arResult['SIDE'] && count($arResult['SIDE']) > 2) {
            ?>
            <div class="block_main_canvas_item">
                <div class="block_main_canvas__small_canvas_block">

                    <?
                    for ($i = 2; $i < 4; ++$i)
                    {
                        $this->AddEditAction($arResult['SIDE'][$i]['ID'], $arResult['SIDE'][$i]['EDIT_LINK'], CIBlock::GetArrayByID($arResult['SIDE'][$i]["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arResult['SIDE'][$i]['ID'], $arResult['SIDE'][$i]['DELETE_LINK'], CIBlock::GetArrayByID($arResult['SIDE'][$i]["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        if($lazyLoad)
                        {
                            $strLazyLoad = 'src="'.SITE_TEMPLATE_PATH.'/assets/img/loader_lazy.svg" data-src="'.$arResult['SIDE'][$i]['PREVIEW_PICTURE']['SRC'].'" class="lazy"';
                        }else{
                            $strLazyLoad = 'src="'.$arResult['SIDE'][$i]['PREVIEW_PICTURE']['SRC'].'"';
                        }

                        ?>
                        <div class="block_main_canvas__small_canvas"
                             id="<?= $this->GetEditAreaID($arResult['SIDE'][$i]['ID']) ?>">
                            <a
                                <?= ($item['PROPERTIES']['NEW_TAB']['VALUE'] == 'Y') ? 'target="_blank"' : '' ?>
                                    class="block_main_canvas__small_canvas_link <?=$hoverClass?>"
                                    href="<?= $arResult['SIDE'][$i]['PROPERTIES']['URL']['VALUE'] ?>"  title="<?=$arResult['SIDE'][$i]['NAME']?>">
                                <div class="block_main_canvas__small_canvas_img">
                                    <div class="block_main_canvas__small_canvas_title fonts__small_title">
                                        <?= $arResult['SIDE'][$i]['NAME'] ?>
                                    </div>
                                    <img <?=$strLazyLoad?>
                                         width="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['WIDTH']?>"
                                         height="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['HEIGHT']?>"
                                         title="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['TITLE']?>"
                                         alt="<?=$arResult['SIDE'][$i]['PREVIEW_PICTURE']['ALT']?>"
                                    >
                                    <?if($lazyLoad):?>
                                        <span class="loader-lazy"></span>
                                    <?endif;?>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $('.slider-canvas').owlCarousel({
        stopOnHover: true,
        loop: true,
        items: 1,
        nav: true,
        navText: ["", ""],
        autoplay: false,
        smartSpeed: 500,
    });
</script>
