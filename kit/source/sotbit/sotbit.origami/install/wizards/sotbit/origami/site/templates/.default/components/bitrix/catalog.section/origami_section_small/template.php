  <? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Helper\Config;
use Bitrix\Main\Page\Asset;

  Asset::getInstance()->addJs(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/js/swiper.js");
  Asset::getInstance()->addCss(SITE_DIR . "local/templates/sotbit_origami/assets/plugin/swiper5.2.0/css/swiper.css");

$this->setFrameMode(true);

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'LABEL_PROP' => $arParams['LABEL_PROP'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
    '~COMPARE_URL_TEMPLATE' => str_replace('/bitrix/components/sotbit/crosssell.collection/ajax.php', '', $arResult['~COMPARE_URL_TEMPLATE']),
    '~COMPARE_DELETE_URL_TEMPLATE' => str_replace('/bitrix/components/sotbit/crosssell.collection/ajax.php', '', $arResult['~COMPARE_DELETE_URL_TEMPLATE']),
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
	'USE_VOTE_RATING' => $arParams['USE_VOTE_RATING'],
	'IBLOCK_ID' => $arParams['IBLOCK_ID'],
	'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
);

if($arResult['ITEMS'])
{?>
  <div class="small-product">
      <p class="small-product__title fonts__middle_title"><?=$arParams['SECTION_NAME']?></p>
      <div class="small-product__product-wrapper swiper-container">   <!-- <== if !slide, swiper => remove -->
          <div class="swiper-wrapper">          <!-- <== if !slide, swiper => remove -->
          <?
          foreach($arResult['ITEMS'] as $item)
          {
              $uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
              ?>
			  <div class="swiper-slide">        <!-- <== if !slide, swiper => remove -->
		          <?
	              $APPLICATION->IncludeComponent(
	                  'bitrix:catalog.item',
	                  'origami_item_small',
	                  array(
	                      'RESULT' => array(
	                          'ITEM' => $item,
	                          'AREA_ID' => $this->GetEditAreaId($uniqueId),
	                          'TYPE' => 'CARD_LIGHT',
                              'LAZY_LOAD' => (Config::get('LAZY_LOAD') == "Y"),
	                          'BIG_LABEL' => 'N',
	                          'BIG_DISCOUNT_PERCENT' => 'N',
	                          'BIG_BUTTONS' => 'N',
	                          'SCALABLE' => 'N'
	                      ),
	                      'PARAMS' => $generalParams
	                          + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
	                  ),
	                  $component,
	                  array('HIDE_ICONS' => 'Y')
	              );
	              ?>
			  </div>
	          <?
          }
          ?>
          </div>
          <div class="small-product__slider-button small-product__slider-button--next">
<!--              <svg class="swiper-button-icon" width="10" height="10">-->
<!--                  <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_chevron_right_small"></use>-->
<!--              </svg>-->
          </div>
          <div class="small-product__slider-button small-product__slider-button--prev">
<!--              <svg class="swiper-button-icon" width="10" height="10">-->
<!--                  <use xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_chevron_right_small"></use>-->
<!--              </svg>-->
          </div>
      </div>
  </div>
    <script>
        var swiper = new Swiper('.small-product__product-wrapper', {
            spaceBetween: 30,
            watchOverflow: true,
            freeMode: true,
            freeModeMomentumRatio: 0.7,
            navigation: {
                nextEl: '.small-product__slider-button--next',
                prevEl: '.small-product__slider-button--prev',
            },
            breakpoints: {
                320: {
                    spaceBetween: 21,
                    slidesPerView: 'auto',
                },
                768: {
                    spaceBetween: 30,
                    slidesPerView: 'auto',
                },

                1344: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                }
            }
        });
    </script>
<?}
