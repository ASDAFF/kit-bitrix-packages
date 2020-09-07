<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\FileHelper;
use intec\core\io\Path;

/**
 * @var CBitrixComponent $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$template = null;

if ($this->initComponentTemplate("", $this->getSiteTemplateId())) {
    $cache = false;
    $template = $this->getTemplate();
    $handler = Path::from('@root/'.$template->GetFolder().'/component.begin.php');

    if (FileHelper::isFile($handler->value))
        include($handler->value);

    if ($cache) {
        if ($this->startResultCache()) {
            $this->IncludeComponentTemplate();
        }
    } else {
        $this->IncludeComponentTemplate();
    }

    $template = $this->getTemplate();
    $handler = Path::from('@root/'.$template->GetFolder().'/component.end.php');

    if (FileHelper::isFile($handler->value))
        include($handler->value);
}