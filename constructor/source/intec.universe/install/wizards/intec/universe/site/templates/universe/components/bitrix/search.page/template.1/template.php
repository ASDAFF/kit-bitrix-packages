<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

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

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];
?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-search-page c-search-page-template-1">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="search-page-form-wrap">
                <div class="search-page-form">
                    <form action="" method="get">
                        <div class="intec-grid intec-grid-a-v-center intec-grid-i-h-5">
                            <div class="intec-grid-item-auto intec-grid-item-900 search-page-input">
                                <input class="search-page-suggest" type="text" name="q" value="<?= $arResult["REQUEST"]["QUERY"] ?>" size="40" />
                            </div>
                            <div class="intec-grid-item-auto search-page-button-wrap">
                                <?= Html::tag('button', Loc::getMessage('C_SEARCH_PAGE_TEMPLATE_1_GO'), [
                                    'type' => 'submit',
                                    'class' => [
                                        'intec-ui' => [
                                            '',
                                            'control-button',
                                            'scheme-current',
                                            'mod-round-2'
                                        ],
                                        'search-page-button'
                                    ]
                                ]) ?>
                            </div>
                        </div>
                        <input type="hidden" name="how" value="<?= $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
                    </form>
                </div>

                <?php if (isset($arResult["REQUEST"]["ORIGINAL_QUERY"])) { ?>
                    <div class="search-page-language-guess">
                        <?= Loc::getMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>')) ?>
                    </div>
                <?php } ?>
            </div>

            <?php if ($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false) { ?>
            <?php } elseif ($arResult["ERROR_CODE"] != 0) { ?>
                <div class="search-page-error">
                    <p><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_ERROR") ?></p>
                    <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                        <?= $arResult["ERROR_TEXT"]; ?>
                    </div>
                    <p><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_CORRECT_AND_CONTINUE") ?></p>
                    <br /><br />
                    <p><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SINTAX") ?><br /><b><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_LOGIC") ?></b></p>
                    <table class="search-page-table">
                        <tr>
                            <td class="search-page-table-operator">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_OPERATOR") ?>
                            </td>
                            <td class="search-page-table-synonim">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SYNONIM") ?>
                            </td>
                            <td class="search-page-table-description">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_DESCRIPTION") ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="search-page-table-operator">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_AND") ?>
                            </td>
                            <td class="search-page-table-synonim">
                                and, &amp;, +
                            </td>
                            <td class="search-page-table-description">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_AND_ALT") ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="search-page-table-operator">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_OR") ?>
                            </td>
                            <td class="search-page-table-synonim">
                                or, |
                            </td>
                            <td class="search-page-table-description">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_OR_ALT") ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="search-page-table-operator">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_NOT") ?>
                            </td>
                            <td class="search-page-table-synonim">
                                not, ~
                            </td>
                            <td class="search-page-table-description">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_NOT_ALT") ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="search-page-table-operator">( )</td>
                            <td class="search-page-table-synonim">&nbsp;</td>
                            <td class="search-page-table-description">
                                <?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_BRACKETS_ALT") ?>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } elseif (count($arResult["SEARCH"]) > 0) { ?>
                <?php if ($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"] ?>

                <div class="search-page-sort-wrap">
                    <?php $sLink = '';
                        $sLink .= $arResult["REQUEST"]["FROM"] ? '&amp;from='.$arResult["REQUEST"]["FROM"]: '';
                        $sLink .= $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: '';
                    ?>
                    <div class="search-page-sort intec-grid intec-grid-wrap intec-grid-i-10">
                        <?php if ($arResult["REQUEST"]["HOW"] == "d") { ?>
                            <div class="intec-grid-item-auto">
                                <?= Html::tag('a', Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SORT_RANK"), [
                                    'href' => $arResult["URL"].'&amp;how=r'.$sLink,
                                    'class' => [
                                        'search-page-sort-item',
                                        'intec-cl-text-hover'
                                    ]
                                ]) ?>
                            </div>
                            <div class="intec-grid-item-auto">
                                <span class="search-page-sort-item" data-active="true"><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SORT_DATE") ?></span>
                            </div>
                        <?php } else { ?>
                            <div class="intec-grid-item-auto">
                                <span class="search-page-sort-item" data-active="true"><?= Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SORT_RANK") ?></span>
                            </div>
                            <div class="intec-grid-item-auto">
                                <?= Html::tag('a', Loc::getMessage("C_SEARCH_PAGE_TEMPLATE_1_SORT_DATE"), [
                                    'href' => $arResult["URL"].'&amp;how=d'.$sLink,
                                    'class' => [
                                        'search-page-sort-item',
                                        'intec-cl-text-hover'
                                    ]
                                ]) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="search-page-items">
                    <?php foreach($arResult['SEARCH'] as $arItem) {
                        $sPicture = $arItem['PICTURE'];

                        if (!empty($sPicture)) {
                            $sPicture = CFile::ResizeImageGet($sPicture, array('width' => 120, 'height' => 80, BX_RESIZE_IMAGE_PROPORTIONAL_ALT));

                            if (!empty($sPicture))
                                $sPicture = $sPicture['src'];
                        }

                        if (empty($sPicture))
                            $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                        ?>
                        <div class="search-page-item intec-grid intec-grid-i-h-12">
                            <div class="intec-grid-item-auto">
                                <?= Html::tag('a', '', [
                                    'href' => $arItem["URL"],
                                    'class' => [
                                        'search-page-picture',
                                        'intec-image-effect'
                                    ],
                                    'data' => [
                                        'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                        'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                    ],
                                    'style' => [
                                        'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$sPicture.'\')' : null
                                    ]
                                ]) ?>
                            </div>
                            <div class="intec-grid-item">
                                <div class="search-page-chain-wrap intec-grid intec-grid-i-h-10 intec-grid-a-v-center">
                                    <?php if ($arItem["CHAIN_PATH"]) { ?>
                                        <div class="search-page-chain intec-grid-item-auto">
                                            <?= $arItem["CHAIN_PATH"] ?>
                                        </div>
                                    <?php } ?>
                                    <div class="search-page-date intec-grid-item-auto">
                                        <?=$arItem["DATE_CHANGE"] ?>
                                    </div>
                                </div>
                                <div class="search-page-name-wrap">
                                    <a class="search-page-name intec-cl-text-hover" href="<?= $arItem["URL"] ?>">
                                        <?= $arItem["TITLE_FORMATED"] ?>
                                    </a>
                                </div>
                                <div class="search-page-description">
                                    <?echo $arItem["BODY_FORMATED"] ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"] ?>
            <?php } else { ?>
                <div class="intec-ui intec-ui-control-alert intec-ui-scheme-current intec-ui-m-b-20">
                    <?= Loc::getMessage('C_SEARCH_PAGE_TEMPLATE_1_NOTHING_TO_FOUND') ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>