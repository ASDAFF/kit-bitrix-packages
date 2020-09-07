<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Sotbit\Origami\Helper\Config;

$this->addExternalJs($templateFolder."/mobile.js");
$this->addExternalCss($templateFolder."/mobile.css");
?>

<div class="bx-sidebar-block bx_filter_wrapper">
    <div class="bx_filter">
        <div class="bx_filter_section">
            <div class="block_main_left_menu__title fonts__main_text active">
                <div>
                    <?=GetMessage('CT_BCSF_FILTER')?>
                    <span class="block_main_left_menu__title-toggle"></span>
                </div>
            </div>

            <form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">

                <?foreach($arResult["HIDDEN"] as $arItem):?>
                    <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
                <?endforeach;?>
                <input type="hidden" name="refresh_values">

                <?
                //prices
                foreach($arResult["ITEMS"] as $key => $arItem)
                {
                    $key = $arItem["ENCODED_ID"];
                    if(isset($arItem["PRICE"])):
                        if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                            continue;

                        $step_num = 4;
                        $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
                        $prices = array();
                        if (Bitrix\Main\Loader::includeModule("currency"))
                        {
                            for ($i = 0; $i < $step_num; $i++)
                            {
                                $prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
                            }
                            $prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
                        }
                        else
                        {
                            $precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
                            for ($i = 0; $i < $step_num; $i++)
                            {
                                $prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $precision, ".", "");
                            }
                            $prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                        }
                        ?>
                        <div class="bx_filter_parameters_box active active" data-propid="P<?=$arItem["ID"]?>">
                            <span class="bx_filter_container_modef"></span>
                            <div class="bx_filter_parameters_box_title fonts__middle_text" onclick="smartFilter.hideFilterProps(this)">
                                <span>
                                    <span class="item_name"><?=$arItem["NAME"]?></span>
                                    <span class="number_selected_items"></span>
                                    <svg class="horizontal_filter-icon_cancel" width="8px" height="8px">
                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
                                    </svg>
                                    <span class="selected_items"></span>
                                </span>
                                <span class="bx_filter_arrow"></span>
                                <i data-role="prop_angle" class="icon-nav_button"></i>
                            </div>
                            <div class="bx_filter_block bx_filter_block_wrapper" data-role="bx_filter_block">

                                <div class="bx_filter_parameters_box_container">
                                    <div class="bx_filter_parameters_box_container_block_wrapper">
                                        <div class="bx_filter_parameters_box_container_block">
                                            <div class="bx_filter_input_container">
                                                <input
                                                    class="min-price fonts__middle_comment"
                                                    type="text"
                                                    name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                                    id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                                    value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                                    placeholder="<?=number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");?>"
                                                />
                                            </div>
                                        </div>

                                        <div class="bx_filter_parameters_box_container_block">
                                            <div class="bx_filter_input_container">
                                                <input
                                                    class="max-price fonts__middle_comment"
                                                    type="text"
                                                    name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                                    id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                                    value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                                    size="5"
                                                    onkeyup="smartFilter.keyup(this)"
                                                    placeholder="<?=number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");?>"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <div class="bx_ui_slider_track" id="drag_track_<?=$key?>">
                                        <?for($i = 0; $i <= $step_num; $i++):?>
                                            <div class="bx_ui_slider_part fonts__small_comment p<?=$i+1?>">
                                                <span><?=$prices[$i]?></span>
                                            </div>
                                        <?endfor;?>

                                        <div class="bx_ui_slider_pricebar_VD" style="left: 0;right: 0;"
                                             id="colorUnavailableActive_<?=$key?>"></div>
                                        <div class="bx_ui_slider_pricebar_VN" style="left: 0;right: 0;"
                                             id="colorAvailableInactive_<?=$key?>"></div>
                                        <div class="bx_ui_slider_pricebar_V"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
                                        <div class="bx_ui_slider_range" id="drag_tracker_<?=$key?>"  style="left: 0%; right: 0%;">
                                            <a class="bx_ui_slider_handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
                                            <a class="bx_ui_slider_handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?
                    $arJsParams = array(
                        "leftSlider" => 'left_slider_'.$key,
                        "rightSlider" => 'right_slider_'.$key,
                        "tracker" => "drag_tracker_".$key,
                        "trackerWrap" => "drag_track_".$key,
                        "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                        "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                        "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                        "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                        "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                        "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                        "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
                        "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                        "precision" => $precision,
                        "colorUnavailableActive" => 'colorUnavailableActive_'.$key,
                        "colorAvailableActive" => 'colorAvailableActive_'.$key,
                        "colorAvailableInactive" => 'colorAvailableInactive_'.$key,
                        "price_filter_from" => GetMessage('CT_BCSF_FILTER_FROM'),
                        "price_filter_to" => GetMessage('CT_BCSF_FILTER_TO'),
                    );
                    ?>
                        <script type="text/javascript">
                            BX.ready(function() {
                                if(typeof window.trackBarOptions === 'undefined') {
                                    window.trackBarOptions = {};
                                }
                                window.trackBarOptions['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window.trackBarOptions['<?=$key?>']);
                                window.filterSmartKey = '<?=$key?>';
                            });
                        </script>
                    <?endif;
                }

                //not prices
                foreach($arResult["ITEMS"] as $key => $arItem)
                {
                    if(
                        empty($arItem["VALUES"])
                        || isset($arItem["PRICE"])
                    )
                        continue;

                    if (
                        $arItem["DISPLAY_TYPE"] == "A"
                        && (
                            $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                        )
                    )
                        continue;
                    ?>

                    <div class="bx_filter_parameters_box <?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>active<?endif?><?if ($arItem["CODE"] == 'RAZMER'):?> filter-size<?endif?>" data-propid="<?=$arItem["ID"]?>">
                        <span class="bx_filter_container_modef"></span>
                        <div class="bx_filter_parameters_box_title fonts__middle_text" onclick="smartFilter.hideFilterProps(this)">
                            <span>
                                <span class="item_name"><?=$arItem["NAME"]?></span>
                                 <span class="number_selected_items"></span>
                                            <svg class="horizontal_filter-icon_cancel" width="8px" height="8px">
                                                                         <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_cancel_filter_small"></use>
                                                                     </svg>
                                <span class="selected_items"></span>
                            </span>
                            <span class="bx_filter_arrow"></span>
                            <i data-role="prop_angle" class="icon-nav_button"></i>
                        </div>
                        <div class="bx_filter_block bx_filter_block_wrapper" data-role="bx_filter_block">
                            <div class="bx_filter_parameters_box_container">
                                <?
                                $arCur = current($arItem["VALUES"]);
                                switch ($arItem["DISPLAY_TYPE"])
                                {
                                case "A"://NUMBERS_WITH_SLIDER
                                    ?>
                                    <div class="bx_filter_parameters_box_container_block_wrapper">
                                        <div class="bx_filter_parameters_box_container_block">
                                            <!-- <i class="bx-ft-sub"><?//= GetMessage("CT_BCSF_FILTER_FROM") ?></i> -->
                                            <div class="bx_filter_input_container">
                                                <input
                                                        class="min-price fonts__middle_comment"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", ""); ?>"
                                                />
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container_block">
                                            <!-- <i class="bx-ft-sub"><?//=GetMessage("CT_BCSF_FILTER_TO") ?></i> -->
                                            <div class="bx_filter_input_container">
                                                <input
                                                        class="max-price fonts__middle_comment"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", ""); ?>"
                                                />
                                            </div>
                                        </div>
                                        </div>
                                            <div class="bx_ui_slider_track" id="drag_track_<?= $key ?>">
                                                <?
                                                $precision = $arItem["DECIMALS"] ? $arItem["DECIMALS"] : 0;
                                                $step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
                                                $value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
                                                $value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
                                                $value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
                                                $value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
                                                $value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
                                                ?>
                                                <div class="bx_ui_slider_part fonts__small_comment p1"><span><?= $value1 ?></span></div>
                                                <div class="bx_ui_slider_part fonts__small_comment p2"><span><?= $value2 ?></span></div>
                                                <div class="bx_ui_slider_part fonts__small_comment p3"><span><?= $value3 ?></span></div>
                                                <div class="bx_ui_slider_part fonts__small_comment p4"><span><?= $value4 ?></span></div>
                                                <div class="bx_ui_slider_part fonts__small_comment p5"><span><?= $value5 ?></span></div>

                                                <div class="bx_ui_slider_pricebar_VD" style="left: 0;right: 0;"
                                                    id="colorUnavailableActive_<?= $key ?>"></div>
                                                <div class="bx_ui_slider_pricebar_VN" style="left: 0;right: 0;"
                                                    id="colorAvailableInactive_<?= $key ?>"></div>
                                                <div class="bx_ui_slider_pricebar_V" style="left: 0;right: 0;"
                                                    id="colorAvailableActive_<?= $key ?>"></div>
                                                <div class="bx_ui_slider_range" id="drag_tracker_<?= $key ?>"
                                                    style="left: 0;right: 0;">
                                                    <a class="bx_ui_slider_handle left" style="left:0;"
                                                    href="javascript:void(0)" id="left_slider_<?= $key ?>"></a>
                                                    <a class="bx_ui_slider_handle right" style="right:0;"
                                                    href="javascript:void(0)" id="right_slider_<?= $key ?>"></a>
                                                </div>
                                            </div>
                                <?
                                $arJsParams = array(
                                    "leftSlider" => 'left_slider_'.$key,
                                    "rightSlider" => 'right_slider_'.$key,
                                    "tracker" => "drag_tracker_".$key,
                                    "trackerWrap" => "drag_track_".$key,
                                    "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                                    "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                                    "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                                    "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                                    "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                    "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                    "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
                                    "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                                    "precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
                                    "colorUnavailableActive" => 'colorUnavailableActive_'.$key,
                                    "colorAvailableActive" => 'colorAvailableActive_'.$key,
                                    "colorAvailableInactive" => 'colorAvailableInactive_'.$key,
                                    "price_filter_from" => GetMessage('CT_BCSF_FILTER_FROM'),
                                    "price_filter_to" => GetMessage('CT_BCSF_FILTER_TO'),
                                );
                                ?>
                                    <script type="text/javascript">
                                        BX.ready(function() {
                                            if(typeof window.trackBarOptions === 'undefined') {
                                                window.trackBarOptions = {};
                                            }
                                            window.trackBarOptions['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                            window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window.trackBarOptions['<?=$key?>']);
                                            window.filterSmartKey = '<?=$key?>';
                                        });
                                    </script>
                                <?
                                break;
                                case "B"://NUMBERS
                                ?>
                                   <div class="bx_filter_parameters_box_container_block_wrapper">
                                        <div class="bx_filter_parameters_box_container_block">
                                            <!-- <i class="bx-ft-sub"><?//= GetMessage("CT_BCSF_FILTER_FROM") ?></i> -->
                                            <div class="bx_filter_input_container fonts__middle_comment">
                                                <input
                                                        class="min-price"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", ""); ?>"
                                                />
                                            </div>
                                        </div>
                                        <div class="bx_filter_parameters_box_container_block">
                                            <!-- <i class="bx-ft-sub"><?//= GetMessage("CT_BCSF_FILTER_TO") ?></i> -->
                                            <div class="bx_filter_input_container fonts__middle_comment">
                                                <input
                                                        class="max-price"
                                                        type="text"
                                                        name="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                        id="<?
                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                        value="<?
                                                        echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                        size="5"
                                                        onkeyup="smartFilter.keyup(this)"
                                                        placeholder="<?= number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", ""); ?>"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "G"://CHECKBOXES_WITH_PICTURES
                                ?>
                                    <div class="checkboxes_with_pictures-wrapper">
                                        <div class="bx-filter-param-btn-inline checkboxes_with_pictures">
                                        <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                            <input
                                                style="display: none"
                                                type="checkbox"
                                                name="<?=$ar["CONTROL_NAME"]?>"
                                                id="<?=$ar["CONTROL_ID"]?>"
                                                value="<?=$ar["HTML_VALUE"]?>"
                                                <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                            />
                                            <?
                                            $class = "";
                                            if ($ar["CHECKED"])
                                                $class.= " bx-active";
                                            if ($ar["DISABLED"])
                                                $class.= " disabled";
                                            ?>
                                            <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
                                                <span class="bx-filter-param-btn bx-color-sl">
                                                    <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                        <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');" title="<?=$ar["VALUE"]?>"></span>
                                                    <?endif?>
                                                </span>
                                            </label>
                                        <?endforeach?>
                                        </div>
                                    </div>
									<?
									break;
								case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
									?>
									<div class="bx-filter-param--checkbox-pict-label">
										<div class="bx-filter-param-btn-block">
										<?foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="checkbox"
												name="<?=$ar["CONTROL_NAME"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE"]?>"
												<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
											/>
											<?
											$class = "";
											if ($ar["CHECKED"])
												$class.= " bx-active";
											if ($ar["DISABLED"])
												$class.= " disabled";
											?>
											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
												<span class="bx-filter-param-btn bx-color-sl">
													<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                        <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                    <?endif?>
												</span>
                                                    <span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
                                                        //												if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                        //													?><!-- (<span data-role="count_--><?//=$ar["CONTROL_ID"]?><!--">--><?// echo $ar["ELEMENT_COUNT"]; ?><!--</span>)--><?//
                                                        //												endif;?>
                                                </span>
                                                </label>
                                            <?endforeach?>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "P"://DROPDOWN
                                ?>
                                    <div class="">
                                        <div class="bx-filter-select-container">
                                            <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                                <div class="bx-filter-select-text" data-role="currentOption">
                                                    <?
                                                    foreach ($arItem["VALUES"] as $val => $ar)
                                                    {
                                                        if ($ar["CHECKED"])
                                                        {
                                                            echo $ar["VALUE"];
                                                            $checkedItemExist = true;
                                                        }
                                                    }
                                                    if (!$checkedItemExist)
                                                    {
                                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx-filter-select-arrow"></div>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                                    id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                                    value=""
                                                />
                                                <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <input
                                                        style="display: none"
                                                        type="radio"
                                                        name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                                        id="<?=$ar["CONTROL_ID"]?>"
                                                        value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                    />
                                                <?endforeach?>
                                                <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
                                                    <ul>
                                                        <li>
                                                            <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                            </label>
                                                        </li>
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):
                                                            $class = "";
                                                            if ($ar["CHECKED"])
                                                                $class.= " selected";
                                                            if ($ar["DISABLED"])
                                                                $class.= " disabled";
                                                            ?>
                                                            <li>
                                                                <label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
                                                            </li>
                                                        <?endforeach?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                                ?>
                                    <div class="">
                                        <div class="bx-filter-select-container">
                                            <div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
                                                <div class="bx-filter-select-text fix" data-role="currentOption">
                                                    <?
                                                    $checkedItemExist = false;
                                                    foreach ($arItem["VALUES"] as $val => $ar):
                                                        if ($ar["CHECKED"])
                                                        {
                                                            ?>
                                                            <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                            <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                        <?endif?>
                                                            <span class="bx-filter-param-text">
																<?=$ar["VALUE"]?>
															</span>
                                                            <?
                                                            $checkedItemExist = true;
                                                        }
                                                    endforeach;
                                                    if (!$checkedItemExist)
                                                    {
                                                        ?><span class="bx-filter-btn-color-icon all"></span> <?
                                                        echo GetMessage("CT_BCSF_FILTER_ALL");
                                                    }
                                                    ?>
                                                </div>
                                                <div class="bx-filter-select-arrow"></div>
                                                <input
                                                    style="display: none"
                                                    type="radio"
                                                    name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                                    id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
                                                    value=""
                                                />
                                                <?foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <input
                                                        style="display: none"
                                                        type="radio"
                                                        name="<?=$ar["CONTROL_NAME_ALT"]?>"
                                                        id="<?=$ar["CONTROL_ID"]?>"
                                                        value="<?=$ar["HTML_VALUE_ALT"]?>"
                                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                    />
                                                <?endforeach?>
                                                <div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
                                                    <ul>
                                                        <li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
                                                            <label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
                                                                <span class="bx-filter-btn-color-icon all"></span>
                                                                <? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
                                                            </label>
                                                        </li>
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):
                                                            $class = "";
                                                            if ($ar["CHECKED"])
                                                                $class.= " selected";
                                                            if ($ar["DISABLED"])
                                                                $class.= " disabled";
                                                            ?>
                                                            <li>
                                                                <label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
                                                                    <?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                        <span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
                                                                    <?endif?>
                                                                    <span class="bx-filter-param-text">
																	<?=$ar["VALUE"]?>
																</span>
                                                                </label>
                                                            </li>
                                                        <?endforeach?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?
                                break;
                                case "K"://RADIO_BUTTONS
                                ?>
                                    <div class="radio_container_wrapper">
                                       <div id="style-overflow-search">
                                            <?foreach($arItem["VALUES"] as $val => $ar):?>
                                                <div class="radio_container">
                                                    <input
                                                        id="<?=$ar["CONTROL_ID"]?>"
                                                        name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                                        value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                        type="radio"
                                                        <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                        onclick="smartFilter.click(this)"
                                                        <? echo $ar["DISABLED"] ? 'disabled': '' ?>
                                                    >
                                                    <label  for="<?=$ar["CONTROL_ID"]?>" class="radio-label fonts__main_comment">
                                                        <span class="radio-label_title fonts__main_comment">
                                                            <?=$ar["VALUE"];?><?if ($arParams["DISPLAY_ELEMENT_COUNT"] !==
                                                                "N" && isset($ar["ELEMENT_COUNT"])):
                                                                ?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                            endif;?>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?endforeach;?>
                                       </div>
                                    </div>
                                <?
                                break;
                                case "U"://CALENDAR
                                ?>
                                    <div class="">
                                        <div class="bx_filter_parameters_box_container-block"><div class="bx_filter_input_container bx-filter-calendar-container">
                                                <?$APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                );?>
                                            </div></div>
                                        <div class="bx_filter_parameters_box_container-block"><div class="bx_filter_input_container bx-filter-calendar-container">
                                                <?$APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar',
                                                    '',
                                                    array(
                                                        'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
                                                        'SHOW_INPUT' => 'Y',
                                                        'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                        'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                                        'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                        'SHOW_TIME' => 'N',
                                                        'HIDE_TIMEBAR' => 'Y',
                                                    ),
                                                    null,
                                                    array('HIDE_ICONS' => 'Y')
                                                );?>
                                            </div></div>
                                    </div>
                                <?
                                break;
                                default://CHECKBOXES
                                ?>

                                <?
                                $ch = 0;
                                $maxDisplay = 4;
                                $totalValues = count($arItem["VALUES"]);
                                if($maxDisplay > $totalValues)
                                    $maxDisplay = $totalValues;
                                ?>

                                    <div class="find_property_value_wrapper">
                                        <input type="text" class="find_property_value" placeholder="<?=GetMessage('CT_BCSF_FILTER_SELECT')?>">
                                        <span class="icon-search"></span>
                                    </div>

                                    <div class="blank_ul_wrapper type-checkbox">
                                        <?foreach($arItem["VALUES"] as $val => $ar):
                                            $ch++;
                                            ?>
                                            <?if($ch == $maxDisplay + 1):?>
                                            <div class="hidden_filter_props">
                                        <?endif;?>

                                            <div class="bx_filter_parameters_box_checkbox ">
                                                <input
                                                    type="checkbox"
                                                    id="<? echo $ar["CONTROL_ID"] ?>"
                                                    value="<? echo $ar["HTML_VALUE"] ?>"
                                                    name="<? echo $ar["CONTROL_NAME"] ?>"
                                                    class="checkbox__input"
                                                    <? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
                                                    onclick="smartFilter.click(this)"
                                                    <? echo $ar["DISABLED"] ? 'disabled': '' ?>
                                                >
                                                <label for="<? echo $ar["CONTROL_ID"] ?>" class="checkbox__label fonts__middle_comment">
                                                    <? if($ar['SEO_LINK']):?>
                                                        <a <?= ( $ar['section_filter_link'] != "" ? 'href="'.$ar['section_filter_link'].'"' : "" ) ?>>
                                                            <?=$ar["VALUE"];?><?if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                                ?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                            endif;?>
                                                        </a>
                                                    <?else: ?>
                                                        <?=$ar["VALUE"];?><?if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                            ?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                        endif;?>
                                                    <?endif;?>
                                                </label>
                                            </div>

                                            <?if($ch == $totalValues && $totalValues > $maxDisplay):?>
                                            </div>
                                        <?endif;?>
                                        <?endforeach;?>
                                    </div>

                                    <?
                                if($totalValues > $maxDisplay)
                                {
                                    ?>
                                    <div class="bx_filter_parameters_open_all" onclick="openAllToggle(this)">
                                        <span class="bx_filter_parameters_open_all_open"><?=GetMessage("CT_BCSF_SET_FILTER_ALL_LIST")?></span>
                                        <span class="bx_filter_parameters_open_all_close"><?=GetMessage("CT_BCSF_SET_FILTER_ALL_ROLL_UP")?></span>
                                        <i class="icon-nav_button"></i>
                                    </div>
                                    <?
                                }
                                    ?>

                                <?
                                }
                                ?>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                    <script>getState()</script>
                    <?
                }
                ?>

                <div class="clb"></div>
                <div class="bx_filter_button_box active">
                    <div class="bx_filter_block button">
                        <div class="bx_filter_parameters_box_container">
                            <input class="bx_filter_search_reset fonts__main_comment" type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>">
                            <input class="bx_filter_search_button fonts__main_comment" type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>">
                        </div>
                    </div>
                </div>

                <div class="bx_filter_popup_result fonts__middle_comment <?=Config::get('MENU_SIDE') == 'left' ? 'right' : 'left'?>" id="modef" <?=(!isset($arResult["ELEMENT_COUNT"]))?'style="display:none"':'style="display: inline-block;"'?> >
                    <div class="popup_result__close"></div>
                    <div class="popup_result_info">
                        <i class="fas fa-check"></i>
                        <?=GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                        <span class="arrow"></span>
                    </div>
                    <div class="popup_result_btns" style="display: inline-block;">
                        <a href="<?=$arResult["SEF_DEL_FILTER_URL"]?>" class="del_filter"><?=GetMessage("CT_BCSF_DEL_FILTER")?></a>
                        <a href="<?=$arResult["FILTER_URL"]?>" class="set_filter"><?=GetMessage("CT_BCSF_FILTER_SHOW")?></a>
                        <input type="submit" name="del_filter" id="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>">
                        <input type="submit" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>">
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<?
$arResult["JS_FILTER_PARAMS"]["FILTER_MODE"] = $arParams["FILTER_MODE"];
?>
<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
