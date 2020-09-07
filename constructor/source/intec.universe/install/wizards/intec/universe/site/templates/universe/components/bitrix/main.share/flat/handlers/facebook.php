<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

$name = "facebook";
$title = Loc::getMessage("BOOKMARK_HANDLER_FACEBOOK");
$icon_url_template = "
<a
	href=\"http://www.facebook.com/share.php?u=#PAGE_URL_ENCODED#&t=#PAGE_TITLE_UTF_ENCODED#\"
	onclick=\"window.open(this.href,'','toolbar=0,status=0,width=611,height=231');return false;\"
	target=\"_blank\"
	style=\"background: #39579a\"
	class=\"fb\"
	title=\"".$title."\"
>
    <i class=\"fab fa-facebook\"></i>
</a>\n";
$sort = 200;