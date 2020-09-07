<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\db\ActiveRecords;
use intec\constructor\models\block\Category;
use intec\constructor\models\block\Template;

if (!Loader::includeModule('intec.core') || (
    !Loader::includeModule('intec.constructor') &&
    !Loader::includeModule('intec.constructorlite')
)) return;

$categories = Category::find()->indexBy('code')->all();
$templates = new ActiveRecords();

$category = null;

if (!empty($arCurrentValues['BLOCK_CATEGORY'])) {
    /** @var Category $category */
    $category = $categories->get($arCurrentValues['BLOCK_CATEGORY']);

    if ($category !== null)
        $templates = $category->getTemplates(true);
}

$arParameters = [
    'BLOCK_CATEGORY' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('parameters.category'),
        'TYPE' => 'LIST',
        'VALUES' => $categories->asArray(function ($index, $category) {
            /** @var Category $category */

            return [
                'key' => $category->code,
                'value' => '['.$category->code.'] '.$category->name
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ],
    'BLOCK_CODE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('parameters.block'),
        'TYPE' => 'LIST',
        'VALUES' => $templates->asArray(function ($index, $template) {
            /** @var Template $template */

            return [
                'key' => $template->code,
                'value' => '['.$template->code.'] '.$template->name
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ],
];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];