<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) { die(); }
use Kit\Origami\Helper\Config;
$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
$arVideo = array();
$arFilter = array(
    'IBLOCK_ID' => Config::get("IBLOCK_ID_VLOG"),
    "ACTIVE" => "Y"
);
$rsVideo = CIBlockElement::GetList(array(), $arFilter, false);

$i = 0;
while ($item = $rsVideo->GetNext()) {
    if ($item['ID'] != $arResult['ID']) {
        $arVideo[$i] = $item;
        $arVideo[$i]['ID_VIDEO'] = CIBlockElement::GetProperty(12, $item['ID'])->Fetch()['VALUE'];
        $i++;
    }
}

?>

<div class="vlog-detail">
    <div class="vlog-detail__video-wrapper">
        <div class="vlog-detail__video-container">
            <iframe style="height: 100%; width: 100%;" src="<?=$protocol?>://www.youtube.com/embed/<?=$arResult['PROPERTIES']['VIDEO_LINK']['VALUE']?>?showinfo=0"/></iframe>
        </div>

        <div class="vlog-detail__videos-list-wrapper-outer">
            <div class="vlog-detail__videos-list-wrapper">
                <div class="vlog-detail__videos-list-size">
                    <div class="vlog-detail__videos-list-title">
                        <span><?=GetMessage('OTHER_VIDEO')?></span>
                    </div>

                    <div class="vlog-detail__videos-list-content-scroll">
                        <div class="vlog-detail__videos-list-content">
                            <?foreach ($arVideo as $video):?>
                                <div class="vlog-detail__list-video">
                                    <a href="<?=$video['DETAIL_PAGE_URL'];?>" class="vlog-detail__list-video-link">
                                        <iframe style="height: 100%; width: 100%;" src="<?=$protocol?>://www.youtube.com/embed/<?=$video['ID_VIDEO']?>?showinfo=0"/></iframe>
                                    </a>
                                    <div>
                                        <a href="<?=$video['DETAIL_PAGE_URL'];?>" class="vlog-detail__list-video-title main-color_on-hover">
                                            <span><?=$video['NAME'];?></span>
                                        </a>
                                        <div class="vlog-detail__list-video-date">
                                            <svg class="icon_clock" width="11" height="11">
                                                <use
                                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_clock"></use>
                                            </svg>
                                            <span><?=FormatDate("Q", MakeTimeStamp($video['TIMESTAMP_X']))?> <?=GetMessage('BACK')?></span>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vlog-detail__videos-list-wrapper-shadow"></div>
        </div>
    </div>
    <div class="vlog-detail__description-wrapper">
        <div class="vlog-detail__description">
            <div class="vlog-detail__description-date">
                <span><?=FormatDate("Q", MakeTimeStamp($arResult['TIMESTAMP_X']))?> <?=GetMessage('BACK')?></span>
            </div>
            <div class="vlog-detail__description-text">
                <span><?=$arResult['DETAIL_TEXT']?></span>
            </div>
            <? $arTags = explode(',', $arResult['TAGS']); ?>
            <?if($arTags):?>
                <div class="vlog-detail__description-tags video-tags">
                    <span class="video-tags__title"><?=GetMessage('TAGS')?></span>
                    <?foreach ($arTags as $tag):?>
                        <a class="video-tags__tag main-color_bg-on-hover" href="<?= $arResult['LIST_PAGE_URL'] ?>?tag=<?= $tag ?>&set_filter=Y">
                            <span><?=$tag?></span></a>
                    <?endforeach;?>
                </div>
            <?endif;?>
        </div>
        <div class="vlog-detail__sidebar">
            <div class="vlog-detail__sidebar-subscribe">


