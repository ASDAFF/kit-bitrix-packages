<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
?>

<label data-bx-user-consent="<?=htmlspecialcharsbx($config)?>" class="main-user-consent-request label-cbx">
	<input type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" class="invisible">
    <span class="checkbox">
        <svg width="20px" height="20px" viewBox="0 0 20 20">
            <path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695
            18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305
            1.8954305,1 3,1 Z"></path>
            <polyline points="4 11 8 15 16 6"></polyline>
        </svg>
    </span>
	<span class="feedback_block__compliance_title fonts__middle_comment">
        <?=htmlspecialcharsbx($arResult['INPUT_LABEL'])?>
        <!-- Нажимая на кнопку «Оформить заказ», я даю согласие на обработку персональных данных и соглашаюсь c -->
    </span>
    <!-- <a class="feedback_block__compliance_link fonts__middle_comment" href="">политикой конфиденциальности.</a> -->
</label>
<script type="text/html" data-bx-template="main-user-consent-request-loader">
	<div class="main-user-consent-request-popup">
		<div class="main-user-consent-request-popup-cont">
			<div data-bx-head="" class="main-user-consent-request-popup-header"></div>
			<div class="main-user-consent-request-popup-body">
				<div data-bx-loader="" class="main-user-consent-request-loader">
					<svg class="main-user-consent-request-circular" viewBox="25 25 50 50">
						<circle class="main-user-consent-request-path" cx="50" cy="50" r="20" fill="none" stroke-width="1" stroke-miterlimit="10"></circle>
					</svg>
				</div>
				<div data-bx-content="" class="main-user-consent-request-popup-content">
					<div class="main-user-consent-request-popup-textarea-block">
						<textarea data-bx-textarea="" class="main-user-consent-request-popup-text" disabled></textarea>
					</div>
					<div class="main-user-consent-request-popup-buttons">
						<span data-bx-btn-accept="" class="main-user-consent-request-popup-button main-user-consent-request-popup-button-acc">Y</span>
						<span data-bx-btn-reject="" class="main-user-consent-request-popup-button main-user-consent-request-popup-button-rej">N</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
