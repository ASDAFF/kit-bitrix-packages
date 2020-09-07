<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

$arParams = ArrayHelper::merge([
    'FORM_ID' => null
], $arParams);

$arResult['FORM']['ID'] = $arParams['FORM_ID'];