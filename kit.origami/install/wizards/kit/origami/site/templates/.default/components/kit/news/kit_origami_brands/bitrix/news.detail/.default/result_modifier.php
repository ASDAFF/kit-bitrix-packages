<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->__component->arResultCacheKeys
    = array_merge($this->__component->arResultCacheKeys, [
    'DETAIL_PAGE_URL',
    "PREVIEW_PICTURE",
    "DETAIL_PICTURE",
    "PREVIEW_TEXT",
    "~PREVIEW_TEXT",
    "DETAIL_TEXT",
    "~DETAIL_TEXT",
]);
?>