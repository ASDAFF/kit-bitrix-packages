<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div class="puzzle_block  social_block__wrapper main-container">
	<p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["TITLE"]?>
		<a href="https://www.youtube.com/channel/<?=$arParams["CHANEL_ID"]?>" class="puzzle_block__link fonts__small_text" target="_blank">
            <?=Loc::getMessage("SOTBIT_YOUTUBE_GOTO")?>
			<i class="icon-nav_1"></i>
		</a>
	</p>
	<div class="social_block instagram_youTube">
		<div class="row row-no-margin">
            <?
            if($arResult["VIDEOS"])
            {
                foreach($arResult["VIDEOS"] as $video)
                {
                    echo '
                        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12 mb-3 mt-3 social_block__item">
                          <div class="video">
		                    <iframe allowfullscreen="" allow="autoplay" src="https://www.youtube.com/embed/' . $video["ID"] .'?rel=0&amp;showinfo=0" class="video__media"></iframe>
	                      </div>                                             
                          <div class="social_block__content">
                            <p class="social_block__content_comment fonts__middle_text">' . $video["TITLE"] . '</p>
                            <p class="social_block__content_date fonts__middle_comment">' .
                        Loc::getMessage("SOTBIT_YOUTUBE_PUBLISHED").' '.$video["DATE"] . '</p>
                          </div>
                        </div>
                    ';
                }
            }
            else
            {
                echo '<div class="col-12">'.Loc::getMessage('SOTBIT_YOUTUBE_ERROR').'</div>';
            }
            ?>
		</div>
	</div>
</div>