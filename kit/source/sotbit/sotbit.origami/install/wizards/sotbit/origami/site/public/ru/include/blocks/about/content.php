<?php
global $settings;
?>
<div class="puzzle_block about__puzzle_block main-container">
    <p class="puzzle_block__title fonts__middle_title about__puzzle_block__title">
        <?=$settings['fields']['title']['value']?>
    </p>
    <div class="about_block">
        <div class="about_block__img">
            <?php $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_DIR."include/sotbit_origami/about_image.php"
                )
            );?>
        </div>
        <div class="about_block__content">
            <?php $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_DIR."include/sotbit_origami/about_text.php"
                )
            );?>
        </div>
    </div>
</div>
