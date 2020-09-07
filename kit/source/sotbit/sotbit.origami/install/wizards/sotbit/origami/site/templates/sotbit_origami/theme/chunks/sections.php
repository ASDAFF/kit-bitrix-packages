<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

Asset::getInstance()->addCss(SITE_DIR . "local/templates/sotbit_origami/theme/chunks/style.css");

Loc::loadMessages(__FILE__);

global $addFilter;

$filter = [
    'ACTIVE'    => 'Y',
    "IBLOCK_ID" => \Sotbit\Origami\Helper\Config::get('IBLOCK_ID'),
];

if (!$addFilter) {
    $addFilter = [];
}
$filter = array_merge($filter, $addFilter);

$cacheId = md5(serialize($filter));

$cache = \Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache(36000000, $cacheId, '/sotbit.origami')) {
    $sections = $cache->getVars();
} elseif ($cache->startDataCache()) {
    $sectCnt = [];
    $sections = [];
    $rs = \CIBlockElement::GetList(
        [],
        $filter,
        ['IBLOCK_SECTION_ID'],
        false,
        ['IBLOCK_ID', 'DETAIL_PAGE_URL']
    );
    while ($el = $rs->GetNext()) {
        $sectCnt[$el['IBLOCK_SECTION_ID']] = $el['CNT'];
    }
    if ($sectCnt) {
        $rs = CIBlockSection::GetList(
            [],
            ['ID' => array_keys($sectCnt)]
        );
        while ($section = $rs->GetNext()) {
            $sections[] = [
                'NAME' => $section['NAME'],
                'CNT'  => $sectCnt[$section['ID']],
	            'URL' => $section['SECTION_PAGE_URL']
            ];
        }
    }
    $cache->endDataCache($sections);
}

if ($sections) {
    ?>
    <div class="sections_cnt">
        <div class="sections_cnt__title">
            <?= Loc::getMessage(\SotbitOrigami::moduleId
                .'_SECTIONS_CNT_TITLE') ?>
        </div>
        <div class="sections_cnt__body">
            <?php
            foreach ($sections as $section) {
                ?>
                <a class="sections_cnt__row" href="<?=$section['URL']?>">
                    <div class="sections_cnt__name">
                        <?= $section['NAME'] ?>
                    </div>
                    <div class="sections_cnt__cnt">
                        <?= $section['CNT'] ?>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
?>
