<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\constructor\Module as Constructor;
use intec\constructor\models\block\Template;
use intec\constructor\structure\Block;
use intec\core\net\Url;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 */

if (!Loader::includeModule('intec.core') || (
    !Loader::includeModule('intec.constructor') &&
    !Loader::includeModule('intec.constructorlite')
)) return null;


$data = ArrayHelper::getValue($arParams, 'DATA');

/**
 * Шаблон блока.
 * @var Template $template
 */
$template = ArrayHelper::getValue($data, 'template');

if (!($template instanceof Template)) {
    $code = $arParams['BLOCK_CODE'];
    $template = Template::find()
        ->where(['code' => $code])
        ->one();
}

if (empty($template))
    return null;

$block = ArrayHelper::getValue($data, 'block');

if (!($block instanceof Block))
    $block = $template->getModel();

$arResult['TEMPLATE'] = $template;
$arResult['BLOCK'] = $block;

$url = new Url();
$url->setPathString('/bitrix/admin');

if (Constructor::isLite()) {
    $url->getPath()->add('constructorlite_blocks_editor.php');
} else {
    $url->getPath()->add('constructor_blocks_editor.php');
}

$url->getQuery()->set('template', $template->code);

if ($APPLICATION->GetShowIncludeAreas())
    $this->AddIncludeAreaIcons(
        [[
            'URL' => $url->build(),
            'SRC' => '',
            'TITLE' => Loc::getMessage('c.intec.constructor.block.edit')
        ]]
    );

$this->IncludeComponentTemplate();

return [
    'template' => $template,
    'block' => $block
];