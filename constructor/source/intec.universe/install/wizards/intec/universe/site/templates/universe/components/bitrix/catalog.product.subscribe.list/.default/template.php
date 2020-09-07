<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

CJSCore::init(array('popup'));

$randomString = $this->randString();

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$APPLICATION->setTitle(Loc::getMessage('CPSL_SUBSCRIBE_TITLE_NEW'));

if(!$arResult['USER_ID'] && !isset($arParams['GUEST_ACCESS'])):?>
	<?
	$contactTypeCount = count($arResult['CONTACT_TYPES']);
	$authStyle = 'display: block;';
	$identificationStyle = 'display: none;';
	if(!empty($_GET['result']))
	{
		$authStyle = 'display: none;';
		$identificationStyle = 'display: block;';
	}
	?>

	<div class="row">
		<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
			<div class="alert alert-danger"><?=Loc::getMessage('CPSL_SUBSCRIBE_PAGE_TITLE_AUTHORIZE')?></div>
		</div>
		<? $authListGetParams = array(); ?>
		<div class="col-md-8 col-sm-7" id="catalog-subscriber-auth-form" style="<?=$authStyle?>">
			<?$APPLICATION->authForm('', false, false, 'N', false);?>
			<hr class="bxe-light">
		</div>
	</div>


	<div class="row" id="catalog-subscriber-identification-form" style="<?=$identificationStyle?>">
		<div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">

			<div class="row">
				<div class="col-lg-12 catalog-subscriber-identification-form">
					<h4><?=Loc::getMessage('CPSL_HEADLINE_FORM_SEND_CODE')?></h4>
					<hr class="bxe-light">
					<form method="post">
						<?=bitrix_sessid_post()?>
						<input type="hidden" name="siteId" value="<?=SITE_ID?>">
						<?if($contactTypeCount > 1):?>
							<div class="form-group">
								<label for="contactType"><?=Loc::getMessage('CPSL_CONTACT_TYPE_SELECTION')?></label>
								<select id="contactType" class="form-control" name="contactType">
									<?foreach($arResult['CONTACT_TYPES'] as $contactTypeData):?>
										<option value="<?=intval($contactTypeData['ID'])?>">
											<?=htmlspecialcharsbx($contactTypeData['NAME'])?></option>
									<?endforeach;?>
								</select>
							</div>
						<?endif;?>
						<div class="form-group">
							<?
								$contactLable = Loc::getMessage('CPSL_CONTACT_TYPE_NAME');
								$contactTypeId = 0;
								if($contactTypeCount == 1)
								{
									$contactType = current($arResult['CONTACT_TYPES']);
									$contactLable = $contactType['NAME'];
									$contactTypeId = $contactType['ID'];
								}
							?>
							<label for="contactInputOut"><?=htmlspecialcharsbx($contactLable)?></label>
							<input type="text" class="form-control" name="userContact" id="contactInputOut">
							<input type="hidden" name="subscriberIdentification" value="Y">
							<?if($contactTypeId):?>
								<input type="hidden" name="contactType" value="<?=$contactTypeId?>">
							<?endif;?>
						</div>
						<button type="submit" class="btn btn-default"><?=Loc::getMessage('CPSL_BUTTON_SUBMIT_CODE')?></button>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<h4><?=Loc::getMessage('CPSL_HEADLINE_FORM_FOR_ACCESSING')?></h4>
					<hr class="bxe-light">
					<form method="post">
						<?=bitrix_sessid_post()?>
						<div class="form-group">
							<label for="contactInputCheck"><?=htmlspecialcharsbx($contactLable)?></label>
							<input type="text" class="form-control" name="userContact" id="contactInputCheck" value=
								"<?=!empty($_GET['contact']) ? htmlspecialcharsbx(urldecode($_GET['contact'])): ''?>">
						</div>
						<div class="form-group">
							<label for="token"><?=Loc::getMessage('CPSL_CODE_LABLE')?></label>
							<input type="text" class="form-control" name="subscribeToken" id="token">
							<input type="hidden" name="accessCodeVerification" value="Y">
						</div>
						<button type="submit" class="btn btn-default"><?=Loc::getMessage('CPSL_BUTTON_SUBMIT_ACCESS')?></button>
					</form>
				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		BX.ready(function() {
			if(BX('cpsl-auth'))
			{
				BX.bind(BX('cpsl-auth'), 'click', BX.delegate(showAuthForm, this));
				BX.bind(BX('cpsl-identification'), 'click', BX.delegate(showAuthForm, this));
			}
			function showAuthForm()
			{
				var formType = BX.proxy_context.id.replace('cpsl-', '');
				var authForm = BX('catalog-subscriber-auth-form'),
					codeForm = BX('catalog-subscriber-identification-form');
				if(!authForm || !codeForm || !BX('catalog-subscriber-'+formType+'-form')) return;

				BX.style(authForm, 'display', 'none');
				BX.style(codeForm, 'display', 'none');
				BX.style(BX('catalog-subscriber-'+formType+'-form'), 'display', '');
			}
		});
	</script>
<?endif;

?>
<script type="text/javascript">
	BX.message({
		CPSL_MESS_BTN_DETAIL: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',

		CPSL_MESS_NOT_AVAILABLE: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',
		CPSL_BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_BASKET_REDIRECT');?>',
		CPSL_BASKET_URL: '<?=$arParams["BASKET_URL"];?>',
		CPSL_TITLE_ERROR: '<?=GetMessageJS('CPSL_CATALOG_TITLE_ERROR') ?>',
		CPSL_TITLE_BASKET_PROPS: '<?=GetMessageJS('CPSL_CATALOG_TITLE_BASKET_PROPS') ?>',
		CPSL_BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CPSL_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
		CPSL_BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_SEND_PROPS');?>',
		CPSL_BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_CLOSE') ?>',
		CPSL_STATUS_SUCCESS: '<?=GetMessageJS('CPSL_STATUS_SUCCESS');?>',
		CPSL_STATUS_ERROR: '<?=GetMessageJS('CPSL_STATUS_ERROR') ?>'
	});
</script>
<?

if(!empty($_GET['result']) && !empty($_GET['message']))
{
	$successNotify = strpos($_GET['result'], 'Ok') ? true : false;
	$postfix = $successNotify ? 'Ok' : 'Fail';
	$popupTitle = Loc::getMessage('CPSL_SUBSCRIBE_POPUP_TITLE_'.strtoupper(str_replace($postfix, '', $_GET['result'])));

	$arJSParams = array(
		'NOTIFY_USER' => true,
		'NOTIFY_POPUP_TITLE' => $popupTitle,
		'NOTIFY_SUCCESS' => $successNotify,
		'NOTIFY_MESSAGE' => urldecode($_GET['message']),
	);
	?>
	<script type="text/javascript">
		var <?='jaClass_'.$randomString;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
	</script>
	<?
}

if (!empty($arResult['ITEMS'])) { ?>
    <?= Html::beginTag('div', [
        'id' => $sTemplateId,
        'class' => [
            'ns-bitrix',
            'c-catalog-product-subscribe-list',
            'c-catalog-product-subscribe-list-default'
        ],
        'data' => [
            'columns' => $arParams['LINE_ELEMENT_COUNT']
        ]
    ]) ?>
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="product-subscribe-list-items intec-grid intec-grid-wrap intec-grid-i-15">
                    <?php foreach ($arResult['ITEMS'] as $key => $arItem) {
                    $strMainID = $this->GetEditAreaId($arItem['ID']);

                    $arItemIDs = array(
                        'ID' => $strMainID,
                        'PICT' => $strMainID . '_pict',
                        'SECOND_PICT' => $strMainID . '_secondpict',
                        'MAIN_PROPS' => $strMainID . '_main_props',

                        'QUANTITY' => $strMainID . '_quantity',
                        'QUANTITY_DOWN' => $strMainID . '_quant_down',
                        'QUANTITY_UP' => $strMainID . '_quant_up',
                        'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
                        'BUY_LINK' => $strMainID . '_buy_link',
                        'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
                        'SUBSCRIBE_DELETE_LINK' => $strMainID . '_delete_subscribe',

                        'PRICE' => $strMainID . '_price',
                        'DSC_PERC' => $strMainID . '_dsc_perc',
                        'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

                        'PROP_DIV' => $strMainID . '_sku_tree',
                        'PROP' => $strMainID . '_prop_',
                        'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
                        'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
                    );

                    $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID); ?>
                        <?= Html::beginTag('div', [
                            'id' => $arItemIDs['ID'],
                            'class' => Html::cssClassFromArray([
                                'product-subscribe-list-item' => true,
                                'intec-grid-item' => [
                                    $arParams['LINE_ELEMENT_COUNT'] => true,
                                    '600-1' => $arParams['LINE_ELEMENT_COUNT'] <= 3,
                                    '720-2' => $arParams['LINE_ELEMENT_COUNT'] > 2,
                                    '950-2' =>$arParams['LINE_ELEMENT_COUNT'] > 2,
                                    '1200-2' => $arParams['LINE_ELEMENT_COUNT'] > 3
                                ]
                            ],  true)
                        ]) ?>
                            <div class="product-subscribe-list-item-wrapper intec-grid intec-grid-o-vertical">
                                <div class="product-subscribe-list-item-image">
                                    <?= Html::tag('a', '', [
                                        'class' => 'product-subscribe-list-item-image-wrapper',
                                        'style' => [
                                            'background-image' => 'url('.$arItem['PREVIEW_PICTURE']['SRC'].')'
                                        ],
                                        'href' => $arItem['DETAIL_PAGE_URL'],
                                        'title' => $arItem['NAME'],
                                        'id' => $arItemIDs['PICT']
                                    ]) ?>
                                </div>
                                <div class="product-subscribe-list-item-name">
                                    <a class="product-subscribe-list-item-name-wrapper intec-cl-text-hover" href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
                                </div>
                                <div class="intec-grid-item"></div>
                                <div class="product-subscribe-list-item-offers">
                                <?php if (!empty($arItem['OFFERS']) && !empty($arResult['SKU_PROPS'][$arItem['ID']])) { ?>
                                    <div class="product-subscribe-list-item-offers-wrapper" id="<?= $arItemIDs['PROP_DIV'] ?>">
                                        <?php foreach ($arResult['SKU_PROPS'][$arItem['ID']] as $arProperty) { ?>
                                            <?php $arSkuProps[] = array(
                                                'ID' => $arProperty['ID'],
                                                'SHOW_MODE' => $arProperty['SHOW_MODE'],
                                                'VALUES_COUNT' => $arProperty['VALUES_COUNT']
                                            ); ?>
                                            <div class="product-subscribe-list-item-offer" id="<?= $arItemIDs['PROP'] . $arProperty['ID'] . '_cont' ?>">
                                                <div class="product-subscribe-list-item-offer-title">
                                                    <?= $arProperty['NAME'] ?>
                                                </div>
                                                <div class="product-subscribe-list-item-offer-values" id="<?= $arItemIDs['PROP'] . $arProperty['ID'] . '_list' ?>">
                                                    <?php foreach ($arProperty['VALUES'] as $arValue) { ?>
                                                        <div class="product-subscribe-list-item-offer-value intec-cl-border-hover" data-treevalue="<?= $arProperty['ID'] . '_' . $arValue['ID'] ?>" data-onevalue="<?= $arValue['ID'] ?>">
                                                            <?php if ($arProperty['SHOW_MODE'] == 'PICT' && !empty($arValue['PICT'])) { ?>
                                                                <div class="product-subscribe-list-item-offer-value-image">
                                                                    <div class="product-subscribe-list-item-offer-value-image-name">
                                                                        <?= $arValue['NAME'] ?>
                                                                    </div>
                                                                    <div class="product-subscribe-list-item-offer-value-image-wrapper">
                                                                        <?= Html::tag('div', '', [
                                                                            'class' => 'product-subscribe-list-item-offer-value-image-picture',
                                                                            'style' => [
                                                                                'background-image' => 'url('.$arValue['PICT']['SRC'].')'
                                                                            ]
                                                                        ]) ?>
                                                                    </div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="product-subscribe-list-item-offer-value-text">
                                                                    <?= $arValue['NAME'] ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                </div>
                                <div class="product-subscribe-list-item-unsubscribe">
                                    <?= Html::tag('a', Loc::getMessage('CPSL_TPL_MESS_BTN_UNSUBSCRIBE'), [
                                        'class' => [
                                            'product-subscribe-list-item-unsubscribe-wrapper',
                                            'intec-button',
                                            'intec-button-cl-common'
                                        ],
                                        'href' => 'javascript:void(0)',
                                        'id' => $arItemIDs['SUBSCRIBE_DELETE_LINK']
                                    ]) ?>
                                </div>
                            </div>
                            <?php if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) {
                                $arJSParams = array(
                                    'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                                    'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                                    'SHOW_ADD_BASKET_BTN' => false,
                                    'SHOW_BUY_BTN' => true,
                                    'SHOW_ABSENT' => true,
                                    'PRODUCT' => array(
                                        'ID' => $arItem['ID'],
                                        'NAME' => $arItem['~NAME'],
                                        'PICT' => ('Y' == $arItem['SECOND_PICT']?$arItem['PREVIEW_PICTURE_SECOND']:$arItem['PREVIEW_PICTURE']),
                                        'CAN_BUY' => $arItem["CAN_BUY"],
                                        'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                                        'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                                        'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                                        'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                                        'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                                        'ADD_URL' => $arItem['~ADD_URL'],
                                        'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                                        'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
                                    ),
                                    'BASKET' => array(
                                        'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                                        'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                                        'EMPTY_PROPS' => empty($arItem['PRODUCT_PROPERTIES'])
                                    ),
                                    'VISUAL' => array(
                                        'ID' => $arItemIDs['ID'],
                                        'PICT_ID' => $arItemIDs['PICT'],
                                        'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                        'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                        'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                        'PRICE_ID' => $arItemIDs['PRICE'],
                                        'BUY_ID' => $arItemIDs['BUY_LINK'],
                                        'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
                                        'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
                                    ),
                                    'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
                                );
                                ?>
                                <script type="text/javascript">
                                    var <?=$strObName;?> = new JCCatalogProductSubscribeList(
                                        <?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
                                </script>
                            <?php } else { ?>
                                <?php
                                if($arItem['OFFERS_PROPS_DISPLAY']) {
                                    foreach($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer) {
                                        $strProps = '';
                                        $arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                                    }
                                }
                                $arJSParams = array(
                                    'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
                                    'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                                    'SHOW_ADD_BASKET_BTN' => false,
                                    'SHOW_BUY_BTN' => true,
                                    'SHOW_ABSENT' => true,
                                    'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
                                    'SECOND_PICT' => ($arParams['SHOW_IMAGE'] == "Y" ? $arItem['SECOND_PICT'] : false),
                                    'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
                                    'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
                                    'DEFAULT_PICTURE' => array(
                                        'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                                        'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                                    ),
                                    'VISUAL' => array(
                                        'ID' => $arItemIDs['ID'],
                                        'PICT_ID' => $arItemIDs['PICT'],
                                        'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                                        'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                                        'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                                        'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                                        'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                                        'PRICE_ID' => $arItemIDs['PRICE'],
                                        'TREE_ID' => $arItemIDs['PROP_DIV'],
                                        'TREE_ITEM_ID' => $arItemIDs['PROP'],
                                        'BUY_ID' => $arItemIDs['BUY_LINK'],
                                        'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                                        'DSC_PERC' => $arItemIDs['DSC_PERC'],
                                        'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                                        'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                                        'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
                                    ),
                                    'BASKET' => array(
                                        'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                                        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE']
                                    ),
                                    'PRODUCT' => array(
                                        'ID' => $arItem['ID'],
                                        'NAME' => $arItem['~NAME'],
                                        'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
                                    ),
                                    'OFFERS' => $arItem['JS_OFFERS'],
                                    'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
                                    'TREE_PROPS' => $arSkuProps,
                                    'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
                                );
                                ?>
                                <script type="text/javascript">
                                    var <?=$strObName;?> = new JCCatalogProductSubscribeList(
                                        <?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
                                </script>
                            <?php } ?>
                        <?= Html::endTag('div') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>
<?php } else if (isset($arParams['GUEST_ACCESS'])) { ?>
    <div class="ns-bitrix c-catalog-product-subscribe-list c-catalog-product-subscribe-list-default">
        <div class="intec-content intec-content-visible">
            <div class="intec-content-wrapper">
                <div class="product-subscribe-list-alert intec-ui intec-ui-control-alert intec-ui-scheme-blue">
                    <?= Loc::getMessage('CPSL_SUBSCRIBE_NOT_FOUND') ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>