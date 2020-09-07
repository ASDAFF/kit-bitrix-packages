<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\Html;
use intec\core\helpers\Json;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

?>
<label data-bx-user-consent="<?= Html::encode(Json::htmlEncode($arResult['CONFIG'])) ?>" class="main-user-consent-request intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
    <?= Html::checkbox($arParams['INPUT_NAME'], $arParams['IS_CHECKED'], [
        'value' => 'Y'
    ]) ?>
    <span class="intec-ui-part-selector"></span>
    <span class="intec-ui-part-content">
        <?= Html::encode($arResult['INPUT_LABEL']) ?>
    </span>
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