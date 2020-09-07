<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arTab
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arResult['FORMS']['FEEDBACK_1']['USE']) {

    $arData = $arResult['FORMS']['FEEDBACK_1'];
    
?>
    <div class="catalog-element-block-description-item">
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:main.form',
            'template.1', [
                'ID' => $arData['ID'],
                'TEMPLATE' => $arResult['FORMS']['TEMPLATE'],
                'CONSENT' => $arResult['FORMS']['CONSENT'],
                'NAME' => $arData['FORM']['TITLE'],
                'TITLE' => $arData['TITLE'],
                'DESCRIPTION_SHOW' => $arData['DESCRIPTION']['SHOW'],
                'DESCRIPTION_TEXT' => $arData['DESCRIPTION']['TEXT'],
                'VIEW' => $arData['VIEW'],
                'ALIGN_HORIZONTAL' => $arData['ALIGN']['HORIZONTAL'],
                'THEME' => $arData['THEME'],
                'BACKGROUND_COLOR' => $arData['BG']['COLOR'],
                'BACKGROUND_IMAGE_USE' => $arData['BG']['IMAGE']['SHOW'],
                'BACKGROUND_IMAGE_PATH' => $arData['BG']['IMAGE']['PATH'],
                'BACKGROUND_IMAGE_VERTICAL' => 'center',
                'BACKGROUND_IMAGE_HORIZONTAL' => 'center',
                'BACKGROUND_IMAGE_SIZE' => 'cover',
                'BACKGROUND_IMAGE_REPEAT' => 'no-repeat',
                'BUTTON_TEXT' => $arData['BUTTON']['TEXT'],
                'CACHE_TYPE' => 'N'
            ],
            $component
        ) ?>
    </div>
    <?php unset($arData) ?>
<?php } ?>