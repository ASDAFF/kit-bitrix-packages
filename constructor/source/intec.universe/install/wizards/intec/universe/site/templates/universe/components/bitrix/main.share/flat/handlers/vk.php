<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 */

Loc::loadMessages(__FILE__);

$name = 'vk';
$title = Loc::getMessage('BOOKMARK_HANDLER_VK');
$icon_url_template = "
<a
	href=\"http://vk.com/share.php?url=#PAGE_URL_ENCODED#&title=#PAGE_TITLE_UTF_ENCODED#\"
	onclick=\"window.open(this.href,'','toolbar=0,status=0,width=626,height=436');return false;\"
	target=\"_blank\"
	style=\"background: #446690\"
	class=\"vk\"
	title=\"".$title."\"
>
    <i class=\"fab fa-vk\"></i>
</a>\n";
$sort = 100;