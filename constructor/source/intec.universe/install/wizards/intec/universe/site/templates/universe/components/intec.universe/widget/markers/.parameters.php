<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = array(
    'MARKER_RECOMMENDATION' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MARKERS_MARKER_RECOMMENDATION'),
        'TYPE' => 'STRING'
    ),
    'MARKER_NEW' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MARKERS_MARKER_NEW'),
        'TYPE' => 'STRING'
    ),
    'MARKER_HIT' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MARKERS_MARKER_HIT'),
        'TYPE' => 'STRING'
    ),
    'MARKER_DISCOUNT' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MARKERS_MARKER_DISCOUNT'),
        'TYPE' => 'STRING'
    ),
    'MARKER_DISCOUNT_VALUE' => array(
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('MARKERS_MARKER_DISCOUNT_VALUE'),
        'TYPE' => 'STRING'
    )
);