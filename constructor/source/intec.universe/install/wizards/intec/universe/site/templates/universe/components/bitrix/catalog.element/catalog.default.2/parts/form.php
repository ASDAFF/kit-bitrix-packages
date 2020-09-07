<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?$APPLICATION->IncludeComponent(
    "intec.universe:main.form",
    'template.3',
    array(
        "COMPONENT_TEMPLATE" => "template.3",
        "CONSENT" => $arResult['URL']['CONSENT'],
        "TITLE" => $arResult['FORM']['TITLE'],
        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
        "CACHE_TIME" => $arParams['CACHE_TIME'],
        "FORM1_ID" => $arResult['FORM']['FORM_1_ID'],
        "FORM2_ID" => $arResult['FORM']['FORM_2_ID'],
        "FORM1_NAME" => $arResult['FORM']['FORM_1_NAME'],
        "FORM2_NAME" => $arResult['FORM']['FORM_2_NAME'],
        "BUTTON1_TEXT" => $arResult['FORM']['FORM_1_BUTTON_TEXT'],
        "BUTTON2_TEXT" => $arResult['FORM']['FORM_2_BUTTON_TEXT'],
        "DESCRIPTION" => $arResult['FORM']['DESCRIPTION'],
        "DESCRIPTION_SHOW" => "Y",
        "IMAGE" => $arResult['FORM']['IMAGE']
    ),
    $component
);?>