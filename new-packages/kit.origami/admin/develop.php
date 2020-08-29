<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config;
use Sotbit\Origami\Helper;

require_once($_SERVER["DOCUMENT_ROOT"]
    ."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT']
    .'/bitrix/modules/main/include/prolog_admin.php');
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight("main") < "R") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}
$moduleLoaded = false;
try {
    $moduleLoaded = Loader::includeModule('sotbit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
    echo $e->getMessage();
}

$Site = CSite::GetByID($site)->Fetch();

$css = new Config\Widgets\Code('CUSTOM_CSS');
$css->setPath($Site['DIR'].'include/sotbit_origami/files/custom_style.css');
$scss = new Config\Widgets\Code('CUSTOM_SCSS');
$scss->setPath('/local/templates/sotbit_origami/assets/scss/custom.scss');
$js = new Config\Widgets\Code('CUSTOM_JS');
$js->setPath($Site['DIR'].'include/sotbit_origami/files/custom.js');
$metric = new Config\Widgets\Code('CUSTOM_METRIC');
$metric->setPath($Site['DIR'].'include/sotbit_origami/files/metric.php');

/* css-inline */
$inlineCss = new Config\Widgets\CheckBox('INLINE_CSS_CHECKBOX');
$excludeFileSize = new Config\Widgets\Str('INLINE_CSS_EXCLUDE_FILE');
$inlineCssAdmin = new Config\Widgets\CheckBox('INLINE_CSS_ADMIN_CHECKBOX', array('NOTE'=>Loc::getMessage('sotbit.origami_INLINE_CSS_FIRST_MSG')));
$inlineCssAdmin->setCurrentValue(Helper\Config::getInlineCssAdmin());
$removeKernelCssJs = new Config\Widgets\CheckBox('INLINE_CSS_REMOVE_KERNEL_CSS_JS', array('NOTE'=>Loc::getMessage('sotbit.origami_INLINE_CSS_SECOND_MSG')));
/* \css-inline */

$Group = new Config\Group('MAIN_SETTINGS');
$Group->getWidgets()->addItem($css);
$Group->getWidgets()->addItem($scss);
$Group->getWidgets()->addItem($js);
$Group->getWidgets()->addItem($metric);

$GroupCssInline = new Config\Group('INLINE_CSS');
$GroupCssInline->getWidgets()->addItem($inlineCss);
$GroupCssInline->getWidgets()->addItem($excludeFileSize);
$GroupCssInline->getWidgets()->addItem($inlineCssAdmin);
$GroupCssInline->getWidgets()->addItem($removeKernelCssJs);

$Options = new Config\Admin($site);

$Tab = new Config\Tab('MAIN');
$Tab->getGroups()->addItem($Group);
$Tab->getGroups()->addItem($GroupCssInline);
$Options->getTabs()->addItem($Tab);
$Options->show();

require(
    $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php"
);
?>
