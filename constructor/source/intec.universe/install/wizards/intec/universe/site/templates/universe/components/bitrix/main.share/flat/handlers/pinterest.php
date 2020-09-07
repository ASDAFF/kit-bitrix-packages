<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

$name = "pinterest";
$title = Loc::getMessage("BOOKMARK_HANDLER_PINTEREST");
$icon_url_template = "
<a
	href=\"https://www.pinterest.com/pin/create/button/?url=#PAGE_URL_ENCODED#&description=#PAGE_TITLE_UTF_ENCODED#\"
	data-pin-do=\"buttonPin\"
	data-pin-config=\"above\"
	onclick=\"window.open(this.href,'','toolbar=0,status=0,width=750,height=561');return false;\"
	target=\"_blank\"
	style=\"background: #CB2027\"
	class=\"fb\"
	title=\"".$title."\"
>
    <i class=\"fab fa-pinterest\"></i>
</a>\n";
$sort = 500;