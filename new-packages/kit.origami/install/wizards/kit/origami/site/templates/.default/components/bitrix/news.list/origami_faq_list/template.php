<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Sotbit\Origami\Helper\Config;

global $filterSideFilter;
$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
if ($useRegion && $_SESSION['SOTBIT_REGIONS']['ID']) {
    $filterSideFilter['PROPERTY_REGIONS'] = [
        false,
        $_SESSION['SOTBIT_REGIONS']['ID']
    ];
}
?>
<div class="questions-wrapper">
    <div class="questions">
        <div class="questions__content">
            <? if ($arResult['DESCRIPTION']): ?>
                <span class="questions__content_description"><?= $arResult['DESCRIPTION'] ?></span>
            <? endif; ?>
            <div class="questions__manager-wrapper">
                <?
                if ($useRegion):
                    $APPLICATION->IncludeComponent(
                        "kit:regions.data",
                        "origami_faq_manager",
                        [
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "REGION_FIELDS" => ['UF_REGION_MNGR'],
                            "REGION_ID" => $_SESSION['SOTBIT_REGIONS']['ID']
                        ]
                    );
                else:
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "include/kit_origami/faq_manager.php"
                        )
                    );
                endif;
                ?>
            </div>
            <? if ($arResult['SECTION_NAME']): ?>
                <div class="questions__content_title">
                    <span><?= $arResult['SECTION_NAME'] ?></span>
                </div>
            <? endif; ?>
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <div class="questions__content_question">
                    <div class="question-wrapper">
                        <div class="question-text">
                            <span><?= $item['NAME'] ?></span>
                        </div>
                        <div class="question-toggle">
                            <svg class="question-icon_toggle" width="20" height="11">
                                <use
                                    xlink:href="/local/templates/kit_origami/assets/img/sprite.svg#icon_dropdown_big"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="answer-wrapper">
                        <div class="answer-text">
                            <span><?= $item['PREVIEW_TEXT'] ?></span>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>

        </div>
        <div class="questions__manager_wrapper">
            <div class="anchor"></div>
        </div>
    </div>
</div>


