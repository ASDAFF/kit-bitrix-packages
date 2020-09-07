<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

$name = "gplus";
$title = Loc::getMessage("BOOKMARK_HANDLER_GOOGLE_PLUS");
$icon_url_template = "
<a
	href=\"https://plus.google.com/share?url=#PAGE_URL_ENCODED#\"
	onclick=\"window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=584,height=356');return false;\"
	target=\"_blank\"
	style=\"background: #D95333\"
	class=\"gp\"
	title=\"".$title."\"
>
    <i class=\"fab fa-google-plus\"></i>
</a>\n";
$sort = 300;