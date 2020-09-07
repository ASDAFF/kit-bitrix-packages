<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $settings;

$channelID = "UCljk41PuLLNRkcrxPOkj4Vg";
$videosList = getYoutubeVideos($channelID);
?>

<div class="puzzle_block">
    <p class="puzzle_block__title fonts__middle_title">Наш канал на YouTube
        <a href="https://www.youtube.com/channel/<?=$channelID?>" class="puzzle_block__link fonts__small_text">
            Смотреть все видео
            <i class="icon-nav_1"></i>
        </a>
    </p>
    <div class="social_block instagram_youTube">
        <div class="row row-no-margin">
            <?
            if($videosList)
            {
                foreach($videosList as $item)
                {
                    echo '
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mt-3 social_block__item">
                            <div class="social_block__img">
                                <iframe src="https://www.youtube.com/embed/' . $item["ID"] . '" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            </div>
                            <div class="social_block__content">
                                <p class="social_block__content_comment fonts__middle_text">
                                    <a href="" class="social_block__content_comment_link">' . $item["TITLE"] . '</a>
                                </p>
                                <p class="social_block__content_date fonts__middle_comment">Опубликовано: ' . $item["DATE"] . '</p>
                            </div>
                        </div>
                    ';
                }
            }
            else
            {
                echo '<div class="col-12">Не удалось загрузить видео для канала с данным ID</div>';
            }
            ?>
        </div>
    </div>
</div>
