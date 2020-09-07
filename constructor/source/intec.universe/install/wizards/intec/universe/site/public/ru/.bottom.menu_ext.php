<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $aMenuLinks
 */

global $APPLICATION;

$aMenuLinksExt = [];
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);