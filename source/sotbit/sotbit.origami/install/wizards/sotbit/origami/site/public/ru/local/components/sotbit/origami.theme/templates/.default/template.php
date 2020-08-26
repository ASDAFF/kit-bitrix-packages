<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

$isConstructorMode = Config::get('SITE_BUILDER');

$isIndexPage = null;
$curPage = $APPLICATION->GetCurPage(false);
($curPage == SITE_DIR) ? $isIndexPage = true : $isIndexPage=false;

 $seoModule = \CModule::IncludeModule("sotbit.seometa");

Loc::loadMessages(__FILE__);

Asset::getInstance()->addJs($templateFolder . '/plugins/jquery.cookie.js');

if($arResult['CAN_CHANGE'] === true)
{
    Asset::getInstance()->addJs($templateFolder . '/plugins/ui/jquery-ui.min.js');
    Asset::getInstance()->addCss($templateFolder . '/plugins/ui/jquery-ui.min.css');
	$this->setFrameMode(false);
}
else
{
	$this->setFrameMode(true);
}

$theme = new \Sotbit\Origami\Front\Theme();
$settings = $theme->getSettings();

$curFont = Config::get('FONT_BASE');

if($curFont)
{
    $curFont = str_replace(' ', '+', $curFont);
	Asset::getInstance()->addString('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.$curFont.':300,300i,400,400i,600,600i,700,700i,800,800i">');
}

if($arResult['CAN_CHANGE'] === true)
{
	$sections = [
		'base' => [
            'SITE_BUILDER' => [
                'TYPE' => 'SWITCH',
                'WithOutReload' => 'Y'
            ],
			'COLOR_BASE' => [
				'TYPE' => 'COLOR',
				'VALUES' => Config::getColors(),
                'WithOutReload' => 'Y'
            ],
            'WIDTH' => [
				'TYPE' => 'SELECT',
				'VALUES' => Config::getWidths(),
                'WithOutReload' => 'Y'
            ],
			'FONT_BASE' => [
				'TYPE' => 'FONT',
				'VALUES' => Config::getFonts(),
                'WithOutReload' => 'Y'
            ],
            'FONT_BASE_SIZE' => [
				'TYPE' => 'FONT',
				'VALUES' => Config::getFontsSizes(),
                'WithOutReload' => 'Y'
			],
            'MENU_SIDE' => [
				'TYPE' => 'SELECT',
				'VALUES' => Config::getMenuSide()
            ],
            'QUICK_VIEW' => [
				'TYPE' => 'SWITCH'
			],
			'HOVER_EFFECT' => [
				'TYPE' => 'SELECT',
                'VALUES' => Config::getHoverEffect(),
                'MULTIPLE' => 'Y'
            ],
            'BTN_TOP' => [
                'TYPE' => 'SWITCH'
            ],
            'LAZY_LOAD' => [
                'TYPE' => 'SWITCH'
            ],
            'USE_REGIONS' => [
				'TYPE' => 'SWITCH'
            ],

        ],
        'mainpage' => [
            'TOP_BANNER' => [
                'TYPE' => 'SWITCH',
                'DISABLED' => 'Y'
			],
			'LEAD_CAPTURE_FORM' => [
				'TYPE' => 'SWITCH'
			],
        ],
		'header' => [
            'HEADER_FIX_DESKTOP' => [
                'TYPE' => 'SWITCH',
			],
            'HEADER_FIX_MOBILE' => [
                'TYPE' => 'SWITCH',
            ],
			'HEADER' => [
				'TYPE' => 'ISELECT',
				'VALUES' => Config::getHeaders(),
                'DIR' => \SotbitOrigami::headersDir,
                'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_HEADER')
			]
		],
		'footer' => [
			'FOOTER' => [
				'TYPE' => 'ISELECT',
				'VALUES' => Config::getFooters(),
                'DIR' => \SotbitOrigami::footersDir,
                'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_FOOTER')
			]
		],
		'catalog' => [
            'SECTION_ROOT_TEMPLATE' => [
                'TYPE' => 'ISELECT',
                'DIR' => \SotbitOrigami::sectionRootTemplateDir,
                'VALUES' => Config::getSectionRootTemplate(),
                'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_SECTION_ROOT_TEMPLATE')
            ],
            'FILTER_TEMPLATE' => [
                'TYPE' => 'SELECT',
                'VALUES' => Config::getFilterTemplate()
            ],
//            'SEO_LINK_MODE' => [
//                'TYPE' => 'SWITCH',
//            ],
            'SEO_LINK_MODE' => [
                'TYPE' => 'SELECT',
                'VALUES' => Config::getSeoMode()
//                'DISABLED' => ['SEOMETA_MODE']
            ],
            'FILTER_MODE' => [
				'TYPE' => 'SELECT',
                'VALUES' => Config::getFilterMode(),
            ],
            'PAGINATION' => [
                'TYPE' => 'SELECT',
                'VALUES' => Config::getPagination(),
            ],
            'SIDE_MENU_ON_THE_PRODUCT_PAGE' => [
                'TYPE' => 'SWITCH',
                'DISABLED' => 'Y'
			],
            'DETAIL_PICTURE_DISPLAY_TYPE' => [
				'TYPE' => 'SELECT',
                'VALUES' => Config::getDetailPictureDisplayTypes(),
                'DISABLED' => ['magnifier']
            ],
            'DROPDOWN_SIDE_MENU_VIEW' => [
				'TYPE' => 'SELECT',
                'VALUES' => Config::getDropdownSideMenuViews(),
                'DISABLED' => ['BOTTOM']
            ],
            'PICTURE_SIDE_SECTIONS' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_POPUP_ADD_BASKET' => [
                'TYPE' => 'SWITCH'
            ],
		],
		'detail' => [
            'DETAIL_TEMPLATE' => [
                'TYPE' => 'ISELECT',
                'VALUES' => Config::getMainDetailTemplate(),
                'DIR' => \SotbitOrigami::detailsDir,
                'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_DETAIL_TEMPLATE')
            ],
            'SKU_TYPE_' => [
                'TYPE' => 'SELECT',
                'VALUES' => Config::getSkuDisplayTypes(),
            ],
            'TABS_' => [
                'TYPE' => 'TABS',
            ],
            'SHOW_FOUND_CHEAPER_' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_WANT_GIFT_' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_CHECK_STOCK_' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_ANALOG_' => [
                'TYPE' => 'SWITCH'
            ],
			'SHOW_RECOMMENDATION_' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_BUY_WITH_' => [
                'TYPE' => 'SWITCH'
            ],
            'SHOW_BESTSELLER_' => [
                'TYPE' => 'SWITCH'
            ],
            /*'SHOW_SECTION_POPULAR_' => [
                'TYPE' => 'SWITCH'
            ],*/
            'SHOW_VIEWED_' => [
                'TYPE' => 'SWITCH'
            ],

		],
		'order' => [
			'ORDER_TEMPLATE' => [
				'TYPE' => 'ISELECT',
				'VALUES' => Config::getOrderTemplates(),
                'DIR' => \SotbitOrigami::ordersDir,
                'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_ORDER_TEMPLATE')
			]
		],
        'sections' => [
            'CONTACTS' => [
                'TYPE' => 'ISELECT',
                'VALUES' => Config::getContacts(),
	            'DIR' => \SotbitOrigami::contactsDir,
	            'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_SECTION_NAME_CONTACTS')
            ],
            'PROMOTION_LIST_TEMPLATE' => [
                'TYPE' => 'ISELECT',
                'VALUES' => Config::getPromotionListTemplates(),
	            'DIR' => \SotbitOrigami::promotionsDir,
	            'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_SECTION_NAME_PROMOTION_LIST_TEMPLATE')
            ],
            /*'BLOG_LIST_TEMPLATE' => [
                'TYPE' => 'ISELECT',
                'VALUES' => Config::getBlogListTemplates(),
	            'DIR' => \SotbitOrigami::blogDir,
	            'TITLE' => Loc::getMessage(SotbitOrigami::moduleId . '_SECTION_NAME_BLOG_LIST_TEMPLATE')
            ]*/
        ],
	];

	if(!$seoModule) {
	    $sections['catalog']['SEO_LINK_MODE']['DISABLED'] = array('SEOMETA_MODE');
    }
?>

    <div class="theme-change" id="theme-change">
        <svg class="constructor-switch__item-icon" width="18" height="18">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#gear_wheel_18"></use>
        </svg>
        <svg class="constructor-switch__item-icon constructor-switch__item-icon--two" width="28" height="28">
            <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#gear_wheel_28"></use>
        </svg>
    </div>

<? if($isIndexPage): ?>
    <?if($isConstructorMode == 'N'): ?>



    <?else: ?>
        <div class="constructor-switch " id="switch-constructor">
            <svg class="switch-constructor__on-icon" width="30" height="30">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_constructor"></use>
            </svg>
            <div class=" theme-change__tooltip">
                <p class="theme-change__tooltip-title"><?=Loc::getMessage(SotbitOrigami::moduleId . '_BUILDER_BLOCK_TITLE')?></p>
                <p class="theme-change__tooltip-description"><?=Loc::getMessage(SotbitOrigami::moduleId . '_BUILDER_BLOCK_MESSAGE')?></p>
                <img class="theme-change__tooltip-img" src="/local/components/sotbit/origami.theme/templates/.default/img/backgroung-constructor.jpg" alt="">
            </div>
        </div>
        <?if($arResult['CAN_SAVE'] === true):?>
        <div class="constructor-switch__save <?if(!isset($_COOKIE["origamiConstructMode"]) || $_COOKIE["origamiConstructMode"] != "Y"):?>hide__constructor-switch<?endif?>" id="switch-constructor__save">
            <svg class="switch-constructor__save-icon" width="58" height="58">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_save"></use>
            </svg>
        </div>
        <?endif;?>
        <div class="constructor-switch__off <?if(!isset($_COOKIE["origamiConstructMode"]) || $_COOKIE["origamiConstructMode"] != "Y"):?>hide__constructor-switch<?endif;?>" id="switch-constructor__off">
            <svg class="switch-constructor__off-icon" width="58" height="58">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_constructor"></use>
            </svg>
</div>
    <? endif ?>
<? endif ?>


    <div id="theme-panel" data-id="blocks_panel" class="landing-ui-panel theme-panel landing-ui-panel-content
    landing-ui-panel-block-list <?= ($settings['OPEN'] == 'Y') ? 'landing-ui-show-just' : 'landing-ui-hide' ?>"
    data-is-shown="true" <?= ($settings['OPEN'] == 'Y') ? '' : 'hidden' ?>>
	<div class="landing-ui-panel-content-element landing-ui-panel-content-header">
		<div class="landing-ui-panel-content-title">
			<?= Loc::getMessage(SotbitOrigami::moduleId . '_EDIT') ?>
        </div>
        <div class="landing-ui-panel-content-subtitle">
			<?= Loc::getMessage(SotbitOrigami::moduleId . '_EDIT_SUBTITLE') ?>
		</div>
	</div>
	<div class="landing-ui-panel-content-element landing-ui-panel-content-body">
		<div class="landing-ui-panel-content-body-sidebar">
			<? if($sections)
			{
				foreach ($sections as $key => $section)
				{
                    $active = "";
                    if($_COOKIE['activeTabCode'])
                    {
                        if($key == $_COOKIE['activeTabCode'])
                            $active = "landing-ui-active";
                    }
                    elseif($key == "base")
                    {
                        $active = "landing-ui-active";
                    }
					?>
					<button type="button" class="landing-ui-button landing-ui-button-sidebar landing-ui-button-sidebar-child <?=$active?>" data-id="<?=$key?>">
						<span class="landing-ui-button-text">
							<?= Loc::getMessage(SotbitOrigami::moduleId . '_SECTION_' . $key) ?>
						</span>
					</button>
					<?
				}
			}
			?>
		</div>
		<div class="landing-ui-panel-content-body-content">
			<?
			if($sections)
			{
				foreach ($sections as $key => $section)
				{
                    $activeStyle = "";
                    if($_COOKIE['activeTabCode'])
                    {
                        if($key == $_COOKIE['activeTabCode'])
                            $activeStyle = "";
                        else
                            $activeStyle = "display:none";
                    }
                    elseif($key != 'base')
                    {
                        $activeStyle = 'display:none';
                    }
					?>
                    <div class="options-section-name" data-id="<?=$key.'-name'?>" style="<?=$activeStyle?>">
                        <?=Loc::getMessage(SotbitOrigami::moduleId . '_SECTION_' . $key)?>
                    </div>
					<div class="options-section" data-id="<?=$key?>" style="<?=$activeStyle?>">
						<?
						foreach ($section as $code => $option)
						{
							//printr(array("VAL"=>Config::get($code)));
						    if(!isset($option["MULTIPLE"]) || $option["MULTIPLE"] == "N")
							    $value = Config::get($code);
							else
                                $value = unserialize(Config::get($code));
                            ?>
							<div class="landing-ui-card-block-preview" data-code="<?= $code ?>">
								<div class="landing-ui-card-body">
                                    <?
									switch ($option['TYPE'])
									{
										case 'COLOR':
											?>
											<div class="landing-ui-field landing-ui-field-button-group landing-ui-field-color" data-selector="">
												<div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
												<div class="landing-ui-field-input options-block" data-code="<?= $code ?>">
                                                    <ul class="options-block__colors">
													<?
													$was = false;
													foreach ($option['VALUES'] as $color)
													{
														if($value == $color)
														{
															$was = true;
														}
														?>

														<li class="option option-color notreload landing-ui-button <?= ($value == $color) ? 'landing-ui-button-active' : '' ?>" data-id="<?= $color ?>">
														    <span style="background: <?= $color ?>"></span>
														</li>
														<?
													}
													?>
                                                    </ul>
                                                    <form class="option-block__form">
													<input type="text" class="options-block-custom" minlength="4" maxlength="7"
													       placeholder="<?=Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_CUSTOM_COLOR')?>"
													       name="CUSTOM[<?=$code?>]" value="<?=(!$was)?$value:''?>">
                                                        <button class="option-btn-apply option notreload options-block-custom__btn-save" type="button"><?=Loc::getMessage(SotbitOrigami::moduleId . '_SAVE_UPLOAD')?></button>
                                                        <button class="option-btn-reset option notreload options-block-custom__btn-reset" type="button"><?=Loc::getMessage(SotbitOrigami::moduleId . '_SAVE_CANCEL')?></button>
                                                    </form>
												</div>
											</div>
											<?
											break;
										case 'FONT':
											?>
											<div class="landing-ui-field landing-ui-field-button-group" data-selector="">
												<div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
												<div class="options-block options-block-font" data-code="<?= $code ?>">
													<?
													$was = false;
													foreach ($option['VALUES'] as $font)
													{
														if($value == $font)
														{
															$was = true;
														}
														?>
														<button type="button" class="option notreload <?= ($value == $font) ? 'landing-ui-button-active' : '' ?>" data-id="<?= $font ?>">
                                                            <span style="font-size: <?= $font ?>">
                                                                <?= $font ?>
                                                            </span>
														</button>
														<?
													}
													?>
<!--													<input type="text" class="options-block-custom"-->
<!--													       placeholder="--><?//=Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_CUSTOM')?><!--"-->
<!--													       name="CUSTOM[--><?//=$code?><!--]" value="--><?//=	(!$was) ? $value : ''?><!--">-->
												</div>
											</div>
											<?
											break;
										case 'SELECT':
											?>
											<div class="landing-ui-field landing-ui-field-button-group" data-selector="">
												<div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
												<div class="options-block" data-code="<?= $code ?>">
													<?
													foreach ($option['VALUES'] as $key => $oValue)
													{
														?>
                                                        <? if(isset($option["MULTIPLE"]) & $option['MULTIPLE'] == "Y"):?>
                                                            <button type="button" class="option <? if(isset($option['WithOutReload']) & $option['WithOutReload'] == "Y"):?>notreload<?endif;?> <?= (in_array($key, $value)) ? 'landing-ui-button-active' : '' ?> multiple" data-id="<?= $key ?>"
                                                                <? if($option['DISABLED']) echo (in_array($key, $option['DISABLED'])) ? 'disabled' : '' ?>>
                                                                <span>
                                                                    <?= $oValue ?>
                                                                </span>
                                                            </button>
                                                        <? else: ?>
                                                            <button type="button" class="option  <? if(isset($option['WithOutReload']) & $option['WithOutReload'] == "Y"):?>notreload<?endif;?> <?= ($value == $key) ? 'landing-ui-button-active' : '' ?>" data-id="<?= $key ?>"
                                                                <? if($option['DISABLED']) echo (in_array($key, $option['DISABLED'])) ? 'disabled' : '' ?>>
                                                                <span>
                                                                    <?= $oValue ?>
                                                                </span>
                                                            </button>
                                                        <? endif; ?>
														<?
													}
													?>
												</div>
											</div>
											<?
											break;
										case 'ISELECT':
											?>
											<div class="landing-ui-field landing-ui-field-button-group" data-selector="">
                                                <div class="landing-ui-field-header">
                                                    <?
                                                        if($option['TITLE']) echo $option['TITLE'];
                                                        else echo Loc::getMessage(SotbitOrigami::moduleId . '_CHOICE_OF_TEMPLATE');
                                                    ?>
                                                </div>
                                                <ul class="options-block options-block-iselect options-block__list" data-code="<?= $code ?>">
													<?
													foreach ($option['VALUES'] as $key => $oValue)
													{
                                                        $arOrigamiTheme = [];
													    include($_SERVER["DOCUMENT_ROOT"].$option['DIR'].'/'.strtolower($key)."/description.php");
													    ?>
                                                        <li class="options-block__list-item options-block__list-item--type-<?=$key?>">

                                                            <input id="ids-<?= $code ?>-<?= $key ?>" type="radio" name="<?= $code ?>-change" class="options-block__input option <?= ($value == $key) ? 'landing-ui-button-active' : '' ?>" data-id="<?= $key ?>"/>
                                                            <label class="options-block__label" for="ids-<?= $code ?>-<?= $key ?>">
                                                            <?if(isset($arOrigamiTheme["NAME"])):
                                                                $titleName = $arOrigamiTheme["NAME"];
                                                                ?>
                                                                <div class="option-block__title landing-ui-field-header-iselect"><?=$arOrigamiTheme["NAME"]?></div>
                                                            <?else:
                                                                $titleName = is_numeric($oValue)?$oValue:'';
                                                                ?>
                                                                <div class="option-block__title landing-ui-field-header-iselect"><?=(!is_numeric($oValue))?$oValue:''?></div>
                                                            <?endif;?>
                                                                <?
                                                                if($key == '' && $code == 'DETAIL_TEMPLATE'){
                                                                    $key = '.default';
                                                                }
	                                                            ?>
                                                                <span title="<?=$titleName?>" style='background-image: url("<?=$option['DIR'].'/'.strtolower($key)?>/preview.jpg")'></span>
                                                            </label>
                                                        </li>
														<?
													}
													?>
												</ul>
											</div>
											<?
                                            break;
                                        case 'SWITCH':
											?>
                                            <? if($code == 'SITE_BUILDER'):?>
                                                <div class="landing-ui-field landing-ui-field-button-group landing-ui-field-switch" data-selector="" style="display: none;">
                                                    <div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
                                                    <div class="options-block <?=($option['DISABLED'] == 'Y') ? 'disabled' : ''?>" data-code="<?= $code ?>">
                                                        <div id="toggle-switches" class="option switch notreload <?=($value == 'Y') ? 'switch-on' : ''?> <?=($option['DISABLED'] == 'Y') ? 'disabled' : ''?>"></div>
                                                    </div>
                                                </div>
                                            <? else: ?>
                                                <div class="landing-ui-field landing-ui-field-button-group landing-ui-field-switch" data-selector="">
                                                    <div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
                                                    <div class="options-block <?=($option['DISABLED'] == 'Y') ? 'disabled' : ''?>" data-code="<?= $code ?>">
                                                        <div id="toggle-switches" class="option switch <?=($value == 'Y') ? 'switch-on' : ''?> <?=($option['DISABLED'] == 'Y') ? 'disabled' : ''?>"></div>
                                                    </div>
                                                </div>
                                            <? endif; ?>
											<?
											break;
										case 'TABS':
											$tabs = unserialize($value);
											$oTabs = Config::getTabs();
											if(count($tabs) !== count($oTabs))
											{
											    $tabs = $oTabs;
											}
											if(!$tabs){
												$tabs = Config::getTabs();
											}
											?>
									<div class="options-block" data-code="<?= $code ?>" style="display: none">
										<button type="button" id="tabs_<?=$code?>_value" class="option landing-ui-button-active" data-id='<?=implode('||',$tabs)?>'>
										</button>
									</div>
											<div class="landing-ui-field landing-ui-field-button-group" data-selector="">
												<div class="landing-ui-field-header"><?= Loc::getMessage(SotbitOrigami::moduleId . '_OPTION_' . $code) ?></div>
													<ul id="sortable_<?=$code?>" class="tabs">
														<?
														foreach($tabs as $tab){
															?>
															<li class="ui-state-default" data-code="<?=$tab?>">
															    <span class="swap_icon_wrapper">
                                                                    <svg class="swap_icon" width="10" height="14">
                                                                        <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_swap_settings"></use>
                                                                    </svg>
                                                                </span>
																<span>
																	<?=(Config::get('NAME_TAB_'.$tab.'_'))
																		?Config::get('NAME_TAB_'.$tab.'_')
																		:Loc::getMessage(SotbitOrigami::moduleId.'_TAB_'.$tab)?>
																</span>
																<div class="options-block <?=($option['DISABLED'] == 'Y') ? 'disabled' : ''?>" data-code="ACTIVE_TAB_<?= $tab ?>_">
																	<?
																	$tabActive = Config::get('ACTIVE_TAB_'.$tab.'_');
																	?>
																	<div class="option switch <?=($tabActive == 'Y') ? 'switch-on' : ''?>"></div>
																</div>
															</li>
															<?
														}
														?>
													</ul>
													<script>
														$( function() {
															$( "#sortable_<?=$code?>" ).sortable({
																stop: function( event, ui ) {
																	let arr = [];
																	$('#sortable_<?=$code?> li').each(function(){
																		arr.push($(this).data('code'));
																	});
																	$('#tabs_<?=$code?>_value').attr('data-id',arr.join('||'));
																	$('#tabs_<?=$code?>_value').trigger('click');
																}
															});
															$( "#sortable_<?=$code?>" ).disableSelection();
														} );
													</script>
												</div>
											<?
											break;
									}
									?>
								</div>
							</div>
						    <?
						}
					    ?>
                    </div>
                    <?
				}
			}
			?>
		</div>
	</div>
    <div class="landing-ui-panel-content-element landing-ui-panel-content-footer">
        <?
        if($arResult['CAN_SAVE'] === true)
        {
            ?>
            <button type="button" class="landing-ui-button landing-ui-panel-content-save" data-id="save" title="<?= Loc::getMessage(SotbitOrigami::moduleId . '_SAVE') ?>">
                <span class="landing-ui-button-text"><?= Loc::getMessage(SotbitOrigami::moduleId . '_SAVE') ?></span>
            </button>
            <?
        }
        ?>
        <button type="button" class="landing-ui-button landing-ui-panel-content-close close-for-theme-change" data-id="close" title="<?= Loc::getMessage(SotbitOrigami::moduleId . '_CLOSE') ?>">
            <svg class="constructor-switch__item-icon" width="18" height="18">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#gear_wheel_18"></use>
            </svg>
            <svg class="constructor-switch__item-icon constructor-switch__item-icon--two" width="28" height="28">
                <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#gear_wheel_28"></use>
            </svg>
            <span class="landing-ui-button-text"></span>
        </button>
    </div>
</div>

<div class="overlay"></div>


<script>
	var Theme = new Themes({
		'theme': '#theme-change',
		'close': '.landing-ui-panel-content-close',
		'option': '.option',
        'btnApply':'#options-block-custom__btn-save',
        'btnReset':'#options-block-custom__btn-reset',
        'btnSwitchOn':'#switch-constructor',
        'btnSwitchOff':'#switch-constructor__off',
		'sidebar':'.landing-ui-button-sidebar',
		'save': '#theme-panel .landing-ui-panel-content-save',
		//'save': '#switch-constructor__save',
		'site': '<?=SITE_ID?>',
    });
    $(document).ready(function() {
        if($.cookie('scrollPosition')) {
            $('.landing-ui-panel-content-body-content').scrollTop($.cookie('scrollPosition'));
        }
    });
</script>

<?
}
?>
