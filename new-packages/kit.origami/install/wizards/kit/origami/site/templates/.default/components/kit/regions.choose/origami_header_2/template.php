<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Sotbit\Regions\Config\Option;

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
$frame = $this->createFrame()->begin("");
?>
	<?/*<svg width="18" height="18">
		<use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_location"></use>
	</svg>
	<p><?=Loc::getMessage(SotbitRegions::moduleId.'_YOUR_CITY')?></p>*/?>
	<a href="javascript:return false;" class="select-city__block__text-city__js"><?=$arResult['USER_REGION_NAME']?></a>

		<div class="select-city__dropdown-wrap" style="<?=($arResult['SHOW_POPUP'] == 'Y')?'display:block;':'display:none;'?>">
			<div class="select-city__dropdown">
				<div class="select-city__dropdown__title-wrap">
				<span class="select-city__dropdown__title"><?=Loc::getMessage(SotbitRegions::moduleId.'_YOUR_CITY')?>
                    <?=$arResult['USER_REGION_NAME_LOCATION']?>
				</span>
				</div>
				<div class="select-city__dropdown__choose-wrap">
				<span
						class="select-city__dropdown__choose__yes select-city__dropdown__choose"
						data-id="<?=$arResult['USER_REGION_ID']?>"
				>
					<?=Loc::getMessage(SotbitRegions::moduleId.'_YES')?>
				</span>
					<span class="select-city__dropdown__choose__no select-city__dropdown__choose">
					<?=Loc::getMessage(SotbitRegions::moduleId.'_NO')?>
				</span>
				</div>
			</div>
		</div>


	<div class="select-city__modal">
		<div class="select-city__modal-wrap">
			<div class="select-city__close"></div>
            <?
            if($arResult['REGION_LIST_COUNTRIES']){
                ?>
				<div class="select-city__tabs_wrapper">
					<ul class="select-city__tabs" id="sotbit-regions-tabs">
                        <?
                        foreach ($arResult['REGION_LIST_COUNTRIES'] as $idCountry => $region){
                            if($region['SALE_LOCATION_LOCATION_NAME_NAME']) {
                                ?>
								<li class="select-city__tab <?= ($idCountry
                                    == key($arResult['REGION_LIST_COUNTRIES'])) ? 'active'
                                    : '' ?>" data-country-id="<?= $idCountry ?>">
                                    <?= $region['SALE_LOCATION_LOCATION_NAME_NAME'] ?>
								</li>
                                <?
                            }
                        }
                        ?>
					</ul>
				</div>
                <?
            }
            ?>
			<div class="select-city__modal__title-wrap">
				<p class="select-city__modal__title">
                    <?=$arResult['USER_REGION_FULL_NAME']?>
				</p>
			</div>
			<input class="select-city__input"
			       type="text"
			       name="region-input"
			       id="region-input"
			       placeholder="<?=Loc::getMessage(SotbitRegions::moduleId.'_WRITE_SITY')?>">
            <?

            if($arResult['TITLE_CITIES']) {
                ?>
				<div class="select-city__wrapper__input">
					<div class="select-city__input__comment select-city__under_input">
	                    <?= Loc::getMessage(
	                        'sotbit.regions_EXAMPLE',
	                        [
	                            '#ID0#'   => $arResult['TITLE_CITIES'][0]['ID'],
	                            '#ID1#'   => $arResult['TITLE_CITIES'][1]['ID'],
	                            '#NAME0#' => $arResult['TITLE_CITIES'][0]['SALE_LOCATION_LOCATION_NAME_NAME'],
	                            '#NAME1#' => $arResult['TITLE_CITIES'][1]['SALE_LOCATION_LOCATION_NAME_NAME'],
	                        ]
	                    ) ?>
					</div>
				</div>
                <?
            }
            if($arResult['REGION_LIST_COUNTRIES']){
                ?>
				<div class="select-city__tab_name_content">
                    <?php
                    if($arResult['FAVORITES']){
                        ?>
						<div class="select-city__tab_name_content__big_city"><?=Loc::getMessage(SotbitRegions::moduleId.'_BIG_CITIES')?></div>
                        <?php
                    }
                    ?>
					<div class="select-city__tab_name_content__village"><?=Loc::getMessage(SotbitRegions::moduleId.'_CITIES')?></div>
				</div>
                <?
                foreach ($arResult['REGION_LIST_COUNTRIES'] as $id => $region){
                    ?>
					<div class="select-city__tab_content <?=($id == key($arResult['REGION_LIST_COUNTRIES']))?'active':''?>" data-country-id="<?=$id?>">
						<div class="select-city__list_wrapper">
                            <?if($arResult['FAVORITES'][$id]){
                                ?>
								<div class="select-city__list_wrapper_favorites">
									<div class="select-city__list">
                                        <?
                                        foreach($arResult['FAVORITES'][$id] as $city){
                                            ?>
											<p class="select-city__list_item" data-index="<?= $city['ID'] ?>">
                                                <?= $city['SALE_LOCATION_LOCATION_NAME_NAME'] ?>
											</p>
                                            <?
                                        }
                                        ?>
									</div>
								</div>
                                <?
                            }
                            if($region['CITY']){
                                ?>
								<div id="container_scroll">
									<div class="select-city__list_wrapper_cities content">
                                        <?
                                        foreach ($region['CITY'] as $letter => $cities){
                                            ?>
											<div class="select-city__list_letter_wrapper">
												<div class="select-city__list_letter">
                                                    <?=$letter?>
												</div>
												<div class="select-city__list">
                                                    <?
                                                    foreach ($cities as $city){
                                                        ?>
														<p class="select-city__list_item" data-index="<?= $city['ID'] ?>">
                                                            <?= $city['SALE_LOCATION_LOCATION_NAME_NAME'] ?>
														</p>
                                                        <?
                                                    }
                                                    ?>
												</div>
											</div>
                                            <?
                                        }
                                        ?>
									</div>
								</div>

                                <?php
                            }
                            ?>
						</div>
					</div>
                    <?php
                }
            }
            ?>
		</div>

	</div>

	<div class="modal__overlay"></div>


	<script>
    document.addEventListener('DOMContentLoaded', function () {
		var SotbitRegion = new SotbitRegions({
			'list':<?=CUtil::PhpToJSObject($arResult['REGION_LIST'], false, true); ?>,
			'rootDomain':'<?=$arResult['ROOT_DOMAIN'][0]?>',
			'templateFolder':'<?=$templateFolder?>',
			'componentFolder':'<?=$componentPath?>',
			'singleDomain':'<?=Option::get('SINGLE_DOMAIN',SITE_ID)?>',
			'arParams':'<?=json_encode($arParams)?>'
        });
     });
	</script>


<?$frame->end();?>
