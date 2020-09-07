<?
$APPLICATION->SetTitle("����");
$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
$this->setFrameMode(true);
//$APPLICATION->ShowAjaxHead();

?>
<div class="vlog">
    <div class="vlog__content-wrapper">
        <?if($arResult['PICTURE']):?>
            <div class="vlog__banner-wrapper">
                <img class="vlog__banner" src="<?=CFile::GetPath($arResult['PICTURE'])?>">
            </div>
        <?endif;?>

        <div class="vlog__content">
            <?foreach ($arResult['ITEMS'] as $item):?>
                <div class="vlog__video-container video-container">
                    <a href="<?=$item['DETAIL_PAGE_URL']?>" class="video-container__image-wrapper">
                        <iframe style="height: 100%; width: 100%" src="<?=$protocol?>://www.youtube.com/embed/<?=$item['PROPERTIES']['VIDEO_LINK']['VALUE']?>?showinfo=0"/></iframe>
                        <div class="video-container__date">
                            <svg class="icon_clock" width="11" height="11">
                                <use xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>
                            </svg>
                            <span><?=FormatDate("Q", MakeTimeStamp($item['TIMESTAMP_X']))?> <?=GetMessage('BACK')?></span>
                        </div>
                    </a>

                    <div class="video-container__text-wrapper">
                        <a href="<?=$item['DETAIL_PAGE_URL']?>" class="video-container__title main-color_on-hover">
                            <span><?=$item['NAME']?></span>
                        </a>
                        <?
                            $arTags = explode(',', $item['TAGS']);
                        ?>
                        <?if($item['TAGS']):?>
                            <div class="video-container__tags-wrapper video-tags">
                                <span class="video-tags__title"><?=GetMessage('TAGS')?></span>
                                <?foreach ($arTags as $tag):?>
                                    <a class="video-tags__tag main-color_bg-on-hover" href="?tag=<?=$tag?>&set_filter=Y">
                                        <span><?=$tag?></span></a>
                                <?endforeach;?>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            <?endforeach;?>

        </div>
    </div>
    <div class="vlog__sidebar">

        <?if($arResult['ALL_TAGS']):?>
            <div class="vlog__sidebar-item">
                <div class="vlog__sidebar-item-title">
                    <span><?=GetMessage('TAGS_V2')?></span>
                </div>
                <div class="vlog__sidebar-item-tags-wrapper">
                    <div class="vlog__sidebar-item-tags">
                        <!--  -->
                        <?foreach ($arResult['ALL_TAGS'] as $tag):?>
                            <a class="vlog__sidebar-tag main-color_bg-on-hover" <?if($_REQUEST['tag'] == $tag && $_REQUEST['set_filter'] == 'Y'):?>data-active="true" href="?set_filter=N"<?else:?> href="?tag=<?=$tag?>&set_filter=Y" <?endif;?>><?=$tag?>.
                                <svg class="icon_cancel_filter_small" width="6" height="6">
                                    <use
                                        xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
                                </svg>
                            </a>

                        <?endforeach;?>
                    </div>
                </div>
                <span class="vlog__sidebar-items-tags-show-all"><?=GetMessage('GET_ALL')?></span>
            </div>
        <?endif;?>

        <div class="vlog__sidebar-item">
            <div class="vlog__sidebar-item-title">
                <span><?=GetMessage('LAST_VIDEO')?></span>
            </div>
            <div class="vlog__sidebar-item-video">
                <?$limit = 0;?>
                <?foreach ($arResult['ALL_VIDEOS'] as $item):?>
                    <?if($limit < 5):?>
                        <a href="<?=$item['DETAIL_PAGE_URL']?>" class="vlog__sidebar-item-video-title">
                            <span><?=$item['NAME'];?></span>
                        </a>

                    <? $limit++;
                    endif;?>
                <?endforeach;?>

            </div>
        </div>

        <div class="vlog__sidebar-item">

