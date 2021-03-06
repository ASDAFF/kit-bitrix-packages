<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if (strlen($INPUT_ID) <= 0)
    $INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if (strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if ($arParams["SHOW_INPUT"] !== "N"):?>
    <div class="search_title">
    <span class="inline-search-show basket-block__link">
         <svg class="fa-search_icon" width="20" height="20">
                    <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_search"></use>
         </svg>
    </span>
        <div id="<?= $CONTAINER_ID ?>" class="inline-search-block">
            <div class="puzzle_block puzzle_block_mod">
                <div class="bx-searchtitle">
                    <form action="<?= $arResult["FORM_ACTION"] ?>">
                        <div class="bx-input-group">
                            <span class="icon-search"></span>
                            <input id="<?= $INPUT_ID ?>" type="text" name="q"
                                   value="<?= htmlspecialcharsbx($_REQUEST["q"]) ?>" autocomplete="off"
                                   class="bx-form-control" placeholder="<?= GetMessage('CT_BST_SEARCH_BUTTON') ?>">
                            <span class="bx-input-group-btn">
                            <button class="btn btn-default" type="submit" name="s">
                                <i class="fa fa-search"></i>
                            </button>
                            <span class="icon-close inline-search-hide"></span>
                        </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="overlay-search inline-search-hide"></div>
    </div>
<? endif ?>

<script>
    var searchTitleOptions = {
        "CATALOG_PAGE_URL": '<?=$arParams["PAGE"]?>'
    };
    BX.ready(function () {
        new JCTitleSearch({
            'AJAX_PAGE': '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
            'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
            'INPUT_ID': '<?echo $INPUT_ID?>',
            'MIN_QUERY_LEN': 2
        });
    });
</script>