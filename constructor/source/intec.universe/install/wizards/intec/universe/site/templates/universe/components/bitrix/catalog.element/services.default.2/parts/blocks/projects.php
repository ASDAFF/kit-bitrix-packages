<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 * @var array $arVisual
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 */

?>
<?php if ($arVisual['PROJECTS']['SHOW'] && !empty($arResult['DATA']['PROJECTS']) || $arResult['FORMS']['FEEDBACK_2']['USE']) { ?>
    <div class="catalog-element-projects">
        <?php if ($arVisual['PROJECTS']['SHOW'] && !empty($arResult['DATA']['PROJECTS'])) {

            $arData = $arResult['DATA']['PROJECTS'];

        ?>
            <div class="intec-content">
                <div class="intec-content-wrapper">
                    <div class="catalog-element-title" data-align="center">
                        <?= $arData['NAME'] ?>
                    </div>
                </div>
            </div>
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:main.projects',
                'template.2', [
                    'IBLOCK_ID' => $arData['IBLOCK'],
                    'FILTER' => [
                        'ID' => $arData['ID']
                    ],
                    'HEADER_SHOW' => 'N',
                    'DESCRIPTION_SHOW' => 'N',
                    'WIDE' => $arVisual['PROJECTS']['WIDE'] ? 'Y' : 'N',
                    'COLUMNS' => $arVisual['PROJECTS']['COLUMNS'],
                    'TABS_USE' => 'N',
                    'LINK_USE' => 'Y',
                    'CACHE_TYPE' => 'N',
                    'SORT_BY' => 'SORT',
                    'ORDER_BY' => 'ASC'
                ],
                $component
            ) ?>
            <?php unset($arData) ?>
        <?php } ?>
        <?php if ($arResult['FORMS']['FEEDBACK_2']['USE']) {
            
            $arData = $arResult['FORMS']['FEEDBACK_2'];
            
            $APPLICATION->IncludeComponent(
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
                    'THEME' => $arData['THEME'],
                    'ALIGN_HORIZONTAL' => $arData['ALIGN']['HORIZONTAL'],
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
            );

            unset($arData);
        } ?>
    </div>
<?php } ?>