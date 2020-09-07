<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<div class="puzzle_block main-container">
	<p class="puzzle_block__title fonts__middle_title">
        <?=$arParams["TITLE"]?>
		<a href="https://www.youtube.com/channel/<?=$arParams["CHANEL_ID"]?>" class="puzzle_block__link fonts__small_text" target="_blank">
            <?=Loc::getMessage("SOTBIT_YOUTUBE_GOTO")?>
			<i class="icon-nav_1"></i>
		</a>
	</p>
	<div class="social_block_YT">
		<div class="social_block_YT__wrapper">
            <?
            if($arResult["VIDEOS"])
            {
                foreach($arResult["VIDEOS"] as $video)
                {
                    echo '
                        <div class="social_block_YT__item">
                          <div class="video">
		                    <a class="video__link" href="https://youtu.be/' . $video["ID"] . '">
			                  <picture>
				                <source srcset="https://i.ytimg.com/vi_webp/' . $video["ID"] . '/mqdefault.webp" type="image/webp" media="(min-width: 576px)">
			                    <source srcset="https://i.ytimg.com/vi_webp/' . $video["ID"] . '/sddefault.webp" type="image/webp" media="(max-width: 575px)">
				                <img class="video__media" src="https://i.ytimg.com/vi/' . $video["ID"] . '/maxresdefault.jpg" alt="' . $video["TITLE"] . '">
			                  </picture>
		                    </a>
		                    <button class="video__button" type="button" aria-label="">
                              <svg width="68" height="48" viewBox="0 0 68 48">
                                <path class="video__button-shape" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"></path>
                                <path class="video__button-icon" d="M 45,24 27,14 27,34"></path>
                              </svg>
                            </button>
	                      </div>
                          <div class="social_block_YT__content">
                            <p class="social_block_YT__content_comment fonts__middle_text">' . $video["TITLE"] . '</p>
                            <p class="social_block_YT__content_date fonts__middle_comment">' .
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
