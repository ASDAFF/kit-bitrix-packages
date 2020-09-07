<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\Core;
use Bitrix\Main\Loader;

/**
 * @var $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('iblock'))
	return;

$arResult['FORM_RESULT_STATUS'] = '';
$arResult['FROM_RESULT'] = '';

$request = Core::$app->request;

// Form handler
if ($request->post('submit', false)) {

    $bError = false;

    if ($arParams['USE_CAPTCHA'] == 'Y')
        if (!$APPLICATION->CaptchaCheckCode(
            $request->post('CAPTCHA'),
            $request->post('CAPTCHA_SID')
        )) $bError = true;

	if (!$bError) {
		$fields = array();
		foreach ($request->post() as $key => $value) {
			if (in_array($key, array('bxajaxid', 'AJAX_CALL')))
				continue;

			$fields[$key] = $value;
		}

		if (empty($fields['NAME']) || empty($fields['PREVIEW_TEXT'])) {
			$arResult['FROM_RESULT'] = GetMessage('ERROR_REQUIRED_FIELDS');
			$arResult['FORM_RESULT_STATUS'] = 'error';
		} else {
			$elDefault = array(
				'ACTIVE' => 'N',
				'IBLOCK_SECTION_ID' => false,
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'CODE' => Cutil::translit($request->post('NAME'), 'ru')
			);
			if (!empty($arParams['PROPERTY_ELEMENT_ID'])) {
				$elDefault['PROPERTY_VALUES'][$arParams['PROPERTY_ELEMENT_ID']] = $arParams['ELEMENT_ID'];
			}
			$el = new CIBlockElement;
			$el->Add(array_merge($elDefault, $fields));

			if ($arParams['MAIL_EVENT']) {
				$event = new CEvent();
				$event->SendImmediate(
					$arParams['MAIL_EVENT'],
					SITE_ID,
					array_merge(
						array(
							'ADMIN_EMAIL' => COption::GetOptionString('main', 'email_from', 'default@admin.email')
						),
						$fields
					),
					'N',
					'');
				unset($event);
			}

			$arResult['FORM_RESULT_STATUS'] = 'success';
			$arResult['FROM_RESULT'] = GetMessage('SUCCESS');
		}
	} else {
		$arResult['FORM_RESULT_STATUS'] = 'error';
		$arResult['FROM_RESULT'] = GetMessage('ERROR_CAPTCHA');
	}
}

$arResult['ELEMENTS'] = array();

$maxReviewsCount = (int)$arParams['DISPLAY_REVIEWS_COUNT'];

if (is_numeric($arParams['IBLOCK_ID']) && is_numeric($arParams['ELEMENT_ID']) && !empty($arParams['PROPERTY_ELEMENT_ID']))
{
	$rsReviews = CIBLockElement::GetList(
		array(
			'ID' => 'DESC'
		),
		array(
			'ACTIVE' => 'Y',
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'PROPERTY_'. $arParams['PROPERTY_ELEMENT_ID'] => $arParams['ELEMENT_ID']
		)
	);

	$count = 0;
	while ($rsReview = $rsReviews->GetNextElement()) {
		$arReview = $rsReview->GetFields();
		$arReview['PROPERTIES'] = $rsReview->GetProperties();
		$arResult['ELEMENTS'][] = $arReview;

		if (++$count >= $maxReviewsCount)
			break;
	}
}

$arResult['COMPONENT_HASH'] = 'review_'. spl_object_hash($this);

$this->IncludeComponentTemplate();