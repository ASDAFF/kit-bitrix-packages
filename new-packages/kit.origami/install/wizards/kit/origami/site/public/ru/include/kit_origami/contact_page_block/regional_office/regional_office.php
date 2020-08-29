<?

use Bitrix\Main\Page\Asset;
use Sotbit\Origami\Helper\Config;

$useRegion = (Config::get('USE_REGIONS') == 'Y') ? true : false;
Asset::getInstance()->addcss(SITE_DIR . "include/sotbit_origami/contact_page_block/regional_office/style.css");
Asset::getInstance()->addjs(SITE_DIR . "include/sotbit_origami/contact_page_block/regional_office/script.js");
$shopId = \Sotbit\Origami\Config\Option::get('IBLOCK_ID_SHOP');
$res = CIBlockElement::GetList("ASC", array("IBLOCK_ID" => $shopId));
$rsSections = CIBlockSection::GetList("ASC", array("IBLOCK_ID" => $shopId));

while ($sc = $rsSections->Fetch()) {
    $arSections[] = $sc;
}
while ($it = $res->Fetch()) {
    $arInfo[] = $it;
}

foreach ($arSections as $key => $section) {
    $arSections[$key]['CONTENT_AVAILABILITY'] = false;
    foreach ($arInfo as $item) {
        if ($item['IBLOCK_SECTION_ID'] == $section['ID']) {
            $arSections[$key]['CONTENT_AVAILABILITY'] = true;
        }
    }
}
?>
<div class="contacts-content-size-wrapper">
    <div class="regions-select-buttons-empty-wrapper">
        <div class="full-size-wrapper">
            <div class="hide-buttons-wrapper">
                 <?
                    $i = 0;
                    foreach ($arSections as $section) {
                        if ($section['CONTENT_AVAILABILITY'] && $section['ACTIVE'] == 'Y') {
                            $i++;
                        }
                    }
                 ?>
                <?
                    if ($i > 1) {
                ?>
                    <div class="regions-select-buttons">
                        <div class="show-more-dots">
                            <div class="dots-wrapper">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </div>
                        </div>
                        <?
                            foreach ($arSections as $section) {
                                if ($section['CONTENT_AVAILABILITY'] && $section['ACTIVE'] == 'Y') {
                                ?>
                                <div class="select-region">
                                    <span><?=$section["NAME"]?></span>
                                </div>
                                <?
                                }
                            }
                        ?>
                    </div>
                <?
                    }
                ?>


            </div>
        </div>
    </div>
</div>
<div class="contacts_anchor"></div>
<?
foreach ($arSections as $section) {
    if ($section['CONTENT_AVAILABILITY'] && $section['ACTIVE'] == 'Y') {
        $arPropCoordsYandex = array();
        $arPropCoordsGoogle = array();

        foreach ($arInfo as $item) {
            if ($item["IBLOCK_SECTION_ID"] == $section['ID']) {
                $rsProp = CIBlockElement::GetProperty($section['IBLOCK_ID'], $item['ID']);
                while ($itProp = $rsProp->Fetch()) {
                    if ($itProp['CODE'] == 'YANDEX_MAP') {
                        if ($itProp['VALUE'] != "") {
                            $arPropCoordsYandex[] = explode(',', $itProp['VALUE']);
                        }
                    } elseif ($itProp['CODE'] == 'GOOGLE_MAP') {
                        if ($itProp['VALUE'] != "") {
                            $arPropCoordsGoogle[] = explode(',', $itProp['VALUE']);
                        }
                    }
                }
            }
        }

        if (count($arPropCoordsYandex) > count($arPropCoordsGoogle)) {
            $yandex_lat = $arPropCoordsYandex[0][0];
            $yandex_lon = $arPropCoordsYandex[0][1];
            $position['yandex_lat'] = $yandex_lat;
            $position['yandex_lon'] = $yandex_lon;
            $position['yandex_scale'] = 14;

            foreach ($arPropCoordsYandex as $yandexCoord) {
                $position['PLACEMARKS'][] = array(
                    'LON' => $yandexCoord[1],
                    'LAT' => $yandexCoord[0]
                );
            }
        } else {
            $google_lat = $arPropCoordsGoogle[0][0];
            $google_lon = $arPropCoordsGoogle[0][1];
            $position['google_lat'] = $google_lat;
            $position['google_lon'] = $google_lon;
            $position['google_scale'] = 14;
            foreach ($arPropCoordsGoogle as $googleCoord) {
                $position['PLACEMARKS'][] = array(
                    'LON' => $googleCoord[1],
                    'LAT' => $googleCoord[0]
                );
            }
        }
        ?>
        <div class="contacts-content-regional-wrapper">
        <div class="contacts-content-size-wrapper">
        <div class="regional-office">
        <div class="regional-title">
            <span><?= $section['NAME'] ?></span>
        </div>

        <?
        if ($arPropCoordsYandex || $arPropCoordsGoogle) {
            if (count($arPropCoordsYandex) > count($arPropCoordsGoogle)) {
                $APPLICATION->IncludeComponent(
                    "bitrix:map.yandex.view",
                    ".default",
                    array(
                        "KEY" => "",
                        "INIT_MAP_TYPE" => "MAP",
                        "MAP_DATA" => serialize($position),
                        "MAP_WIDTH" => "100%",
                        "MAP_HEIGHT" => "400",
                        "CONTROLS" => array(
                            0 => "ZOOM",
                            1 => "MINIMAP",
                            2 => "TYPECONTROL",
                            3 => "SCALELINE",
                        ),
                        "OPTIONS" => array(
                            0 => "ENABLE_SCROLL_ZOOM",
                            1 => "ENABLE_DBLCLICK_ZOOM",
                            2 => "ENABLE_DRAGGING",
                        ),
                        "MAP_ID" => "main_region_" . $section['ID'],
                        "COMPONENT_TEMPLATE" => ".default",
                        "API_KEY" => ""
                    ),
                    false
                );
                unset($position);
            } else {
                $APPLICATION->IncludeComponent(
                    "bitrix:map.google.view",
                    ".default",
                    array(
                        "KEY" => "",
                        "INIT_MAP_TYPE" => "MAP",
                        "MAP_DATA" => serialize($position),
                        "MAP_WIDTH" => "100%",
                        "MAP_HEIGHT" => "400",
                        "CONTROLS" => array(
                            0 => "ZOOM",
                            1 => "MINIMAP",
                            2 => "TYPECONTROL",
                            3 => "SCALELINE",
                        ),
                        "OPTIONS" => array(
                            0 => "ENABLE_SCROLL_ZOOM",
                            1 => "ENABLE_DBLCLICK_ZOOM",
                            2 => "ENABLE_DRAGGING",
                        ),
                        "MAP_ID" => "main_region_" . $section['ID'],
                        "COMPONENT_TEMPLATE" => ".default",
                        "API_KEY" => ""
                    ),
                    false
                );
                unset($position);
            }
        }
         ?>

                    <div class="addresses">
                        <?
                        foreach ($arInfo as $item) {
                            if ($item["IBLOCK_SECTION_ID"] == $section['ID']) {
                                $pathImg = CFile::GetPath($item['PREVIEW_PICTURE']);
                                $itemProp = CIBlockElement::GetProperty($section['IBLOCK_ID'], $item['ID']);
                                $arPropsItem = array();
                                while ($itElem = $itemProp->Fetch()) {
                                    if ($itElem['MULTIPLE'] == 'Y') {
                                        $arPropsItem[$itElem['CODE']][] = $itElem;
                                    } else {
                                        $arPropsItem[$itElem['CODE']] = $itElem;
                                    }
                                }
                            ?>
                                <div class="addresses-wrapper">
                                    <div class="regional-office-address">
                                        <div class="office-image-address-wrapper">
                                            <div class="office-image">
                                                <?
                                                if ($pathImg) {
                                                    ?>
                                                    <img src="<?=$pathImg?>" alt="">
                                                    <?
                                                } else {
                                                    ?>
                                                    <img src="<?=SITE_DIR?>include/sotbit_origami/contact_page_block/regional_office/default-no-photo.jpg" alt="">
                                                    <?
                                                }
                                                ?>
                                            </div>
                                            <div class="regional-address">
                                                <span><?=$item['NAME'] . ', ' . $arPropsItem['ADDRESS']['VALUE']?></span>
                                            </div>
                                        </div>
                                        <div class="right-contacts-info">
                                            <div class="regional-address_tablet">
                                                <span><?=$item['NAME'] . ', ' . $arPropsItem['ADDRESS']['VALUE']?></span>
                                            </div>

                                            <?if($arPropsItem['METRO']):?>
                                                <div class="regional-subway">
                                                    <div class="subway-icon-wrapper">
                                                        <svg class="icon-subway" width="12" height="8">
                                                            <use
                                                                xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_metro"></use>
                                                        </svg>
                                                    </div>
                                                    <?
                                                    foreach ($arPropsItem['METRO'] as $subway) {
                                                        echo '<span>' . $subway['VALUE'] . '</span>';
                                                    }
                                                    ?>
                                                </div>
                                            <?endif;?>

                                            <?if($arPropsItem['SCHEDULE']['VALUE']['TEXT']):?>
                                                <div class="regional-mode">
                                                    <svg class="icon-clock" width="11" height="11">
                                                        <use
                                                            xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_clock"></use>
                                                    </svg>
                                                    <div class="mode-text">
                                                        <?=$arPropsItem['SCHEDULE']['VALUE']['TEXT'];?>
                                                    </div>
                                                </div>
                                            <?endif;?>
                                            <?if($arPropsItem['PHONE']):?>
                                                <div class="regional-phones">
                                                    <?
                                                    foreach ($arPropsItem['PHONE'] as $subway) {
                                                        ?>
                                                        <div class="regional-phone-number">

                                                            <a href="tel:<?=str_replace(' ', '', $subway['VALUE']);?>">
                                                                <svg class="icon-phone" width="12" height="12">
                                                                    <use
                                                                        xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                                                                </svg>
                                                                <span><?=$subway['VALUE']?></span>
                                                            </a>
                                                        </div>
                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?
                            }
                        }
                        ?>
                    </div>

                    <?
                    $idSectionManager = CIBlockSection::GetList(array('sort' => 'asc'), array('IBLOCK_ID' => $section['IBLOCK_ID'], 'ID' => $section['ID']), false, array("UF_REGIONAL_MANAGER"))->Fetch()['UF_REGIONAL_MANAGER'];
                    foreach ($idSectionManager as $key => $id) {
                        $rsUserData = CUser::GetByID($id)->Fetch();
                        $arUserData[$key] = array(
                            'NAME' => $rsUserData['NAME'],
                            'LAST_NAME' => $rsUserData['LAST_NAME'],
                            'WORK_POSITION' => $rsUserData['WORK_POSITION'],
                            'WORK_PROFILE' => $rsUserData['WORK_PROFILE'],
                            'WORK_PHONE' => $rsUserData['WORK_PHONE'],
                            'UF_USER_WORK_EMAIL' => $rsUserData['UF_USER_WORK_EMAIL'],
                            'PERSONAL_PHOTO' => $rsUserData['PERSONAL_PHOTO']
                        );
                    }
                    ?>

                    <?foreach ($arUserData as $usDate) { ?>
                        <div class="regional-manager">
                            <div class="manager-photo-wrapper">
                                <div class="manager-photo">
                                    <? if(!$usDate['PERSONAL_PHOTO']): ?>
                                        <img src="<?=SITE_DIR?>include/sotbit_origami/contact_page_block/regional_office/default-no-photo.jpg" alt="">
                                    <? else: ?>
                                        <img src="<?=CFile::GetPath($usDate['PERSONAL_PHOTO']);?>" alt="">
                                    <? endif; ?>
                                </div>
                                <div class="manager-name-wrapper-mobile">
                                    <?if($usDate['WORK_POSITION']):?>
                                        <span class="manager-information-title"><?=$usDate['WORK_POSITION']?></span>
                                    <?endif;?>
                                    <?if($usDate['NAME'] || $usDate['LAST_NAME']):?>
                                        <span class="manager-information-name"><?=$usDate['NAME'] . ' ' . $usDate['LAST_NAME']?></span>
                                    <?endif;?>
                                </div>
                            </div>
                            <div class="manager-responsive-content">
                                <div class="manager-information">
                                    <div class="manager-name-wrapper">
                                        <?if($usDate['WORK_POSITION']):?>
                                            <span class="manager-information-title"><?=$usDate['WORK_POSITION']?></span>
                                        <?endif;?>
                                        <?if($usDate['NAME'] || $usDate['LAST_NAME']):?>
                                            <span class="manager-information-name"><?=$usDate['NAME'] . ' ' . $usDate['LAST_NAME']?></span>
                                        <?endif;?>
                                    </div>
                                    <?if($usDate['WORK_PROFILE']):?>
                                        <span class="manager-information-message">
                                            <?=$usDate['WORK_PROFILE'];?>
                                        </span>
                                    <?endif;?>
                                </div>

                                <div class="manager-contact-panel">
                                    <div class="manager-contacts">
                                        <?if($usDate['WORK_PHONE']):?>
                                            <div class="manager-phone">
                                                <a href="tel:<?=str_replace(' ', '', $usDate['WORK_PHONE']);?>">
                                                    <svg class="icon-phone" width="12" height="12">
                                                        <use
                                                            xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_phone_filled"></use>
                                                    </svg>
                                                    <span><?=$usDate['WORK_PHONE'];?></span>
                                                </a>
                                            </div>
                                        <?endif;?>
                                        <?if($usDate['UF_USER_WORK_EMAIL']):?>
                                            <div class="manager-email">
                                                <a href="mailto:<?=$usDate['UF_USER_WORK_EMAIL'];?>">
                                                    <svg class="icon-mail" width="12" height="9">
                                                        <use
                                                            xlink:href="/local/templates/sotbit_origami/assets/img/sprite.svg#icon_mail_filled_small"></use>
                                                    </svg>
                                                    <span><span><?=$usDate['UF_USER_WORK_EMAIL'];?></span></span>
                                                </a>
                                            </div>
                                        <?endif;?>
                                    </div>
                                    <div class="manager-button">
                                        <div class="manage-get_meeting-button"
                                             onclick="callbackManager('<?= SITE_DIR ?>', '<?= SITE_ID ?>', this)">
                                            <span><?=GetMessage('MAKE_APPOINTMENT')?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? }
                    unset($arUserData);
                    ?>
                </div>
            </div>
        </div>
        <?
    }
}
?>
