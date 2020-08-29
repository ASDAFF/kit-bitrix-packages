<?php

namespace Sotbit\Origami\Helper;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Context;
use Bitrix\Sale\Delivery\Services\Table;
use Bitrix\Sale\Internals\PersonTypeTable;
use Sotbit\Origami\Config\Option;
use Sotbit\Origami\Front\Theme;

Loc::loadMessages(__FILE__);

/**
 * Class Config
 *
 * @package Sotbit\Origami\Helper
 * @author  Sergey Danilkin <s.danilkin@kit.ru>
 */
class Config
{
    /**
     * @return array
     */
    public static function getColors()
    {
        return [
            '#fb0040' => '#fb0040',
            '#ffad00' => '#ffad00',
            '#e65100' => '#e65100',
            '#b41818' => '#b41818',
            '#bd1c3c' => '#bd1c3c',
            '#1ac85c' => '#1ac85c',
            '#1a9f29' => '#1a9f29',
            '#70b7e5' => '#70b7e5',
            '#1976d2' => '#1976d2',
            '#364799' => '#364799',
            '#2d387a' => '#2d387a',
        ];
    }

    /**
     * @param string $code
     * @param string $site
     *
     * @return mixed
     */
    public static function get($code, $site = SITE_ID)
    {
        $theme = new Theme();
        $settings = $theme->getSettings();
        if (isset($settings['OPTIONS'][$code])) {
            return $settings['OPTIONS'][$code];
        } else {
            return Option::get($code, $site);
        }
    }

    public static function getArray($code, $site = SITE_ID)
    {
        $theme = new Theme();
        $settings = $theme->getSettings();
        if (isset($settings['OPTIONS'][$code])) {
            return unserialize($settings['OPTIONS'][$code]);
        } else {
            return unserialize(Option::get($code, $site));
        }
    }

    /**
     * @return array
     */
    public static function getWidths()
    {
        return [
            'calc(100% - 30px)' => '100%',
            '1700px'            => '1700 px',
            '1500px'            => '1500 px',
            '1344px'            => '1344 px',
            '1200px'            => '1200 px',
        ];
    }

    /**
     * @return array
     */
    public static function getFonts()
    {
        return [
            'Ubuntu'    => 'Ubuntu',
            'Open Sans' => 'Open Sans',
            'PT Sans'   => 'PT Sans',
        ];
    }

    /**
     * @return array
     */
    public static function getFontsSizes()
    {
        return [
            '13px' => '13px',
            '14px' => '14px',
            '15px' => '15px',
        ];
    }

    /**
     * @return array
     */
    public static function getImgHover()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getHeaders()
    {
        $return = [];
        $files = scandir($_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::headersDir);
        foreach ($files as $file) {
            if (in_array($file, [
                '.',
                '..',
            ])
            ) {
                continue;
            }

            $name = $file;
            if (file_exists($_SERVER['DOCUMENT_ROOT'].
                \SotbitOrigami::headersDir.'/'.$file.'/settings.php')
            ) {
                $settings = include $_SERVER['DOCUMENT_ROOT'].
                    \SotbitOrigami::headersDir.'/'.$file.'/settings.php';
                $name = $settings['title']['name'];
            }

            $return[$file] = $name;
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getFooters()
    {
        $return = [];
        $files = scandir($_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::footersDir);
        foreach ($files as $file) {
            if (in_array($file, [
                '.',
                '..',
            ])
            ) {
                continue;
            }

            $name = $file;
            if (file_exists($_SERVER['DOCUMENT_ROOT'].
                \SotbitOrigami::footersDir.'/'.$file.'/settings.php')
            ) {
                $settings = include $_SERVER['DOCUMENT_ROOT'].
                    \SotbitOrigami::footersDir.'/'.$file.'/settings.php';
                $name = $settings['title']['name'];
            }

            $return[$file] = $name;

        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getContacts()
    {
        $return = [];
        $files = scandir($_SERVER['DOCUMENT_ROOT'].
            \SotbitOrigami::contactsDir);
        foreach ($files as $file) {
            if (in_array($file, [
                '.',
                '..',
            ])
            ) {
                continue;
            }
            $name = $file;
            if (file_exists($_SERVER['DOCUMENT_ROOT'].
                \SotbitOrigami::contactsDir.'/'.$file.'/settings.php')
            ) {
                $settings = include $_SERVER['DOCUMENT_ROOT'].
                    \SotbitOrigami::contactsDir.'/'.$file.'/settings.php';
                $name = $settings['title']['name'];
            }
            $return[$file] = $name;
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getBack()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getBasket()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getInnerMenu()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getUp()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getUpPosition()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getHeaderFix()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getHeaderFixType()
    {
        return [];
    }

    /**
     * @return array
     */
    public static function getPaymentTypes()
    {
        try {
            Loader::includeModule('sale');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $return = [];
        $rs = PersonTypeTable::getList(
            [
                'select' => [
                    'ID',
                    'NAME',
                ],
                'filter' => ['ACTIVE' => 'Y'],
            ]
        );
        while ($personalType = $rs->fetch()) {
            $return[$personalType['ID']] = '['.$personalType['ID'].'] '
                .$personalType['NAME'];
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getDeliveries()
    {
        try {
            Loader::includeModule('sale');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }
        $return = [];
        $rs = Table::getList(
            [
                'select' => [
                    'ID',
                    'NAME',
                ],
                'filter' => [
                    'ACTIVE'    => 'Y',
                    'PARENT_ID' => 0,
                ],
            ]
        );
        while ($delivery = $rs->fetchObject()) {
            $return[$delivery->getId()] = '['.$delivery->getId().'] '
                .$delivery->getName();
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getPayments()
    {
        $return = [];
        $rs = \Bitrix\Sale\Internals\PaySystemActionTable::getList([
            'select' => [
                'ID',
                'NAME',
            ],
            'filter' => ['ACTIVE' => 'Y'],
        ]);
        while ($payment = $rs->fetchObject()) {
            $return[$payment->getId()] = '['.$payment->getId().'] '
                .$payment->getName();
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getCaptcha()
    {
        return [
            'NO'    => Loc::getMessage(\SotbitOrigami::moduleId
                .'_CAPTCHA_NO'),
            'BITRIX'    => Loc::getMessage(\SotbitOrigami::moduleId
                .'_CAPTCHA_BITRIX'),
            'RECAPTCHA' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_CAPTCHA_RECAPTCHA'),
            'HIDE'      => Loc::getMessage(\SotbitOrigami::moduleId
                .'_CAPTCHA_HIDE'),
        ];
    }

    public static function getHeaderBgColors()
    {
        return [
            'header-three--black' =>  Loc::getMessage(\SotbitOrigami::moduleId
                .'_HEADER_BG_COLOR_1'),
            'header-three--white' =>  Loc::getMessage(\SotbitOrigami::moduleId
                .'_HEADER_BG_COLOR_2'),
            'header-three--gray' =>  Loc::getMessage(\SotbitOrigami::moduleId
                .'_HEADER_BG_COLOR_3'),
        ];
    }

    /**
     * @return array
     */
    public static function getMasksTypes()
    {
        return [
            'FLAGS'    => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FLAGS'),
            'NO_FLAGS'    => Loc::getMessage(\SotbitOrigami::moduleId
                .'_NO_FLAGS'),
        ];
    }

    /**
     * @return array
     */
    public static function getFilterTemplate()
    {
        return [
            'VERTICAL'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_VERTICAL'),
            'HORIZONTAL' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_HORIZONTAL'),
        ];
    }

    /**
     * @return array
     */
    public static function getSectionRootTemplate()
    {
        return [
            'sections'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS'),
            'sections_1'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_1'),
            'sections_2'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_2'),
            'sections_3'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_3'),
            'sections_4'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_4'),
            'sections_5'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_5'),
            'products'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_PRODUCTS'),
            'combine'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_COMBINE'),
        ];
    }

    /**
     * @return array
     */
    public static function getSectionRootTemplateServices()
    {
        return [
            'sections_1'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_SERVICES_1'),
            'sections_2'   => Loc::getMessage(\SotbitOrigami::moduleId.'_SECTION_ROOT_TEMPLATE_SECTIONS_SERVICES_2'),
        ];
    }

    /**
     * @return array
     */
    public static function getRegionTemplate()
    {
        return [
            'kit_regions'   => Loc::getMessage(\SotbitOrigami::moduleId.'_REGION_TEMPLATE_1'),
            'origami_location'   => Loc::getMessage(\SotbitOrigami::moduleId.'_REGION_TEMPLATE_2'),
            'origami_combine'   => Loc::getMessage(\SotbitOrigami::moduleId.'_REGION_TEMPLATE_3'),
        ];
    }

    /**
     * @return array
     */
    public static function getTemplateListView()
    {
        return [
            'card'   => Loc::getMessage(\SotbitOrigami::moduleId.'_TEMPLATE_LIST_CARD'),
            'list'   => Loc::getMessage(\SotbitOrigami::moduleId.'_TEMPLATE_LIST_LIST'),
            'column'   => Loc::getMessage(\SotbitOrigami::moduleId.'_TEMPLATE_LIST_COLUMN'),
        ];
    }

    /**
     * @return array
     */
    public static function getVariantListView()
    {
        return [
            'template_1'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_VARIANT_1'),
            'template_2'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_VARIANT_2'),
            'template_3'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_VARIANT_3'),
            'template_4'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_VARIANT_4'),
            'template_5'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_VARIANT_5'),
        ];
    }

    /**
     * @return array
     */
    public static function getActionProducts()
    {
        return [
            'BUY'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_ACTION_BUY'),
            'DELAY' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_ACTION_DELAY'),
            'COMPARE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_ACTION_COMPARE'),
        ];
    }

    /**
     * @return array
     */
    public static function getShareHandlers()
    {
        return [
            "facebook" => Loc::getMessage(\SotbitOrigami::moduleId .'_FACEBOOK'),
            "mailru" => Loc::getMessage(\SotbitOrigami::moduleId .'_MAILRU'),
            "ok" => Loc::getMessage(\SotbitOrigami::moduleId .'_OK'),
            "telegram" => Loc::getMessage(\SotbitOrigami::moduleId .'_TELEGRAM'),
            "twitter" => Loc::getMessage(\SotbitOrigami::moduleId .'_TWITTER'),
            "viber" => Loc::getMessage(\SotbitOrigami::moduleId .'_VIBER'),
            "vk" => Loc::getMessage(\SotbitOrigami::moduleId .'_VK'),
            "whatsapp" => Loc::getMessage(\SotbitOrigami::moduleId .'_WHATSAPP'),
        ];
    }

    /**
     * @return array
     */
    public static function checkAction($code)
    {
        if(!$code) return 0;
        $obAction = Config::get("ACTION_PRODUCTS");
        $arAction = unserialize($obAction);
        if(in_array($code, $arAction)) return 1;
        else return 0;
    }

    /**
     * @return array
     */
    public static function getSeoMode()
    {
        return [
            'DISABLED'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_SEO_MODE_DISABLED'),
            'SINGLE_LEVEL' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_SEO_MODE_SINGLE_LEVEL'),
            'MULTIPLE_LEVEL' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_SEO_MODE_MULTIPLE_LEVEL'),
            'SEOMETA_MODE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_FILTER_SEO_MODE_SEOMETA_MODE'),
        ];
    }

    /**
     * @return array
     */
    public static function getFilterMode()
    {
        return [
            'PAGE_RELOAD_MODE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_PAGE_RELOAD_MODE'),
            'AJAX_MODE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_AJAX_MODE'),
        ];
    }

    /**
     * @return array
     */
    public static function getProductInRow()
    {
        return [
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];
    }

    /**
     * @return array
     */
    public static function getHideNotAvailable()
    {
        return [
            'Y' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_HIDE_NOT_AVAILABLE_Y'),
            'N' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_HIDE_NOT_AVAILABLE_N'),
            'L' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_HIDE_NOT_AVAILABLE_L'),
        ];
    }

    public static function getIblockBlogTemplates()
    {
        return [
            '.default' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TEMPLATE_BLOG_VARIANT1'),
            'blog_2' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TEMPLATE_BLOG_VARIANT2'),
        ];
    }

    public static function getIblockNewsTemplates()
    {
        return [
            '.default' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TEMPLATE_NEWS_VARIANT1'),
            'news_v2' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TEMPLATE_NEWS_VARIANT2'),
        ];
    }

    /**
     * @return array
     */
    public static function getIblockTypes()
    {
        $return = [];
        try {
            Loader::includeModule('iblock');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $rs = \Bitrix\Iblock\TypeTable::getList(
            [
                'select' => [
                    'ID',
                    'LANG_MESSAGE.NAME',
                ],
                'filter' => [
                    'LANG_MESSAGE.LANGUAGE_ID' => LANGUAGE_ID,
                ],
            ]
        );
        while ($iType = $rs->fetch()) {
            $return[$iType['ID']] = '['.$iType['ID'].'] '.$iType['IBLOCK_TYPE_LANG_MESSAGE_NAME'];
        }

        return $return;
    }


    public static function getIblockIds($site = 's1')
    {
        $return = [];
        $iType = Option::get('IBLOCK_TYPE', $site);
        try {
            Loader::includeModule('iblock');
        } catch (LoaderException $e) {
            echo $e->getMessage();
        }

        $rs = \Bitrix\Iblock\IblockTable::getList(
            [
                'select' => [
                    'ID',
                    'NAME',
                ],
                'filter' => [
                    'IBLOCK_TYPE_ID' => $iType,
                ],
            ]
        );
        while ($iId = $rs->fetch()) {
            $return[$iId['ID']] = $iId['NAME'];
        }

        return $return;
    }

    /**
     * @return array
     */
    public static function getSites()
    {
        $sites = [];
        try {
            $rs = \Bitrix\Main\SiteTable::getList([
                'select' => [
                    'SITE_NAME',
                    'LID',
                ],
                'filter' => ['ACTIVE' => 'Y'],
            ]);
            while ($site = $rs->fetch()) {
                $sites[$site['LID']] = $site['SITE_NAME'];
            }
        } catch (ObjectPropertyException $e) {
            $e->getMessage();
        } catch (ArgumentException $e) {
            $e->getMessage();
        } catch (SystemException $e) {
            $e->getMessage();
        }
        try {
            if (!is_array($sites) || count($sites) == 0) {
                throw new SystemException("Cannt get sites");
            }
        } catch (SystemException $exception) {
            echo $exception->getMessage();
        }

        return $sites;
    }

    /**
     * @param bool|string $site
     *
     * @return bool|mixed|string
     */
    public static function getFavicon($site = SITE_ID)
    {
        return Option::get('FAVICON', $site);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getChunkPath($name = '')
    {
        return $_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::chunksDir.'/'.$name
            .'.php';
    }

    /**
     * @return array
     */
    public static function getProps()
    {
        $return = [];
        $rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
            [
                'select' => [
                    'ID',
                    'PERSON_TYPE_ID',
                    'NAME',
                ],
            ]
        );
        while ($prop = $rs->fetch()) {
            $return[$prop['ID']] = '['.$prop['ID'].']['.$prop['PERSON_TYPE_ID']
                .'] '.$prop['NAME'];
        }

        return $return;
    }

    public static function getBasketTypes()
    {
        return [
            'origami_basket_top' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_BASKET_FIRST'),
            'origami_top_without_basket' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_BASKET_SECOND'),
        ];
    }

    public static function getOrderTemplates()
    {
        $return = [];


        $dirs = [
            $_SERVER['DOCUMENT_ROOT'].\SotbitOrigami::templateDir.'/components/bitrix/sale.order.ajax',
            $_SERVER['DOCUMENT_ROOT'].'/local/templates/.default/components/bitrix/sale.order.ajax',
        ];

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                    $files = scandir($dir);
                    foreach ($files as $file) {
                        if ($file != "kit_order_right_panel") {
                            if (in_array($file, ['.','..',])
                                ||  !is_file($dir.'/'.$file.'/description.php')
                            ) {
                                continue;
                            }
                            $description = include $dir.'/'.$file.'/description.php';
                            $return[$file] = $description['TEMPLATE']['NAME'];
                        }
                    }
            }
        }
        return $return;
    }

    /**
     * @param int $iblock
     *
     * @return array
     */
    public static function getIblockProps($iblock)
    {
        $props = [];
        $rs = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblock],
            'select' => ['ID', 'NAME', 'CODE'],
            'order' => ['CODE' => 'asc']
        ]);
        while ($prop = $rs->fetch()) {
            $props[$prop['CODE']] = '['.$prop['CODE'].'] '.$prop['NAME'];
        }

        return $props;
    }

    /**
     * @param $iblock
     *
     * @return array
     */
    public static function getIblockPropsList($iblock)
    {
        $props = [];
        $rs = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblock,'PROPERTY_TYPE' => 'L'],
            'select' => ['ID', 'NAME', 'CODE'],
            'order' => ['CODE' => 'asc']
        ]);
        while ($prop = $rs->fetch()) {
            $props[$prop['CODE']] = '['.$prop['CODE'].'] '.$prop['NAME'];
        }

        return $props;
    }

    public static function getIblockPropsStrMulti($iblock)
    {
        $props = [];
        $rs = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblock,'PROPERTY_TYPE' => 'S','MULTIPLE' => 'Y'],
            'select' => ['ID', 'NAME', 'CODE'],
            'order' => ['CODE' => 'asc']
        ]);
        while ($prop = $rs->fetch()) {
            $props[$prop['CODE']] = '['.$prop['CODE'].'] '.$prop['NAME'];
        }

        return $props;
    }


    /**
     * @param $iblock
     *
     * @return array
     */
    public static function getIblockPropsIElement($iblock)
    {
        $props = [];
        $rs = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblock,'PROPERTY_TYPE' => 'E'],
            'select' => ['ID', 'NAME', 'CODE'],
            'order' => ['CODE' => 'asc']
        ]);
        while ($prop = $rs->fetch()) {
            $props[$prop['CODE']] = '['.$prop['CODE'].'] '.$prop['NAME'];
        }

        return $props;
    }

    public static function getPromotionListTemplates()
    {
        return [
            'horizontal' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_PROMOTION_LIST_TEMPLATE_HORIZONTAL'),
            'vertical'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_PROMOTION_LIST_TEMPLATE_VERTICAL'),
            'stocks_2'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_PROMOTION_LIST_TEMPLATE_VARIANT3'),
        ];
    }

    public static function getBlogListTemplates()
    {
        return [
            'popular' => Loc::getMessage(\SotbitOrigami::moduleId.'_BLOG_LIST_TEMPLATE_POPULAR'),
        ];
    }

    /**
     * @param array $type
     *
     * @return array
     */
    public static function getBanner($type = [])
    {
		Loader::includeModule('iblock');
        $return = [];
        if ($type) {
            $rs = \CIBlockPropertyEnum::GetList(
                [],
                ['XML_ID' => $type]
            );
            while ($v = $rs->Fetch()) {
                $return[] = $v['ID'];
            }
        }
        if (!$return) {
            $return = [0];
        }

        return $return;
    }

    public static function getHoverEffect()
    {
        return [
            '' => Loc::getMessage(\SotbitOrigami::moduleId.'_HOVER_NO'),
            'hover-highlight' => Loc::getMessage(\SotbitOrigami::moduleId.'_HOVER_1'),
            'hover-zoom' => Loc::getMessage(\SotbitOrigami::moduleId.'_HOVER_2'),
            'hover-square' => Loc::getMessage(\SotbitOrigami::moduleId.'_HOVER_3'),
        ];
    }

    /**
     * @return array
     */
    public static function getMenuSide()
    {
        return [
            'left' => Loc::getMessage(\SotbitOrigami::moduleId.'_MENU_SIDE_LEFT'),
            'right' => Loc::getMessage(\SotbitOrigami::moduleId.'_MENU_SIDE_RIGHT'),
        ];
    }

    public static function getSliderButtons()
    {
        return [
            'default' => Loc::getMessage(\SotbitOrigami::moduleId.'_SLIDER_DEFAULT'),
            'square' => Loc::getMessage(\SotbitOrigami::moduleId.'_SLIDER_SQUARE'),
            'circle' => Loc::getMessage(\SotbitOrigami::moduleId.'_SLIDER_CIRCLE'),
        ];
    }

    /**
     * @return array
     */
    public static function getPropFilterMode()
    {
        return [
            '' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_FILTER_MODE_TEXT'),
            'link' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_FILTER_MODE_LINK'),
            'seometa' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_FILTER_MODE_SEOMETA'),
        ];
    }

    /**
     * @return array
     */
    public static function getPropDisplayMode()
    {
        return [
            'border' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_DISPLAY_MODE_BORDER'),
            'dropdown' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_DISPLAY_MODE_DROPDOWN'),
        ];
    }

    /**
     * @return array
     */
    public static function getPropColorMode()
    {
        return [
            //'color_square' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_COLOR_MODE_COLOR_SQUARE'),
            'color_image' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_COLOR_MODE_COLOR_IMAGE'),
            'offer_image' => Loc::getMessage(\SotbitOrigami::moduleId.'_PROP_COLOR_MODE_OFFER_IMAGE'),
        ];
    }

    /**
     * @return array
     */
    public static function getPagination()
    {
        return [
            'origami' => Loc::getMessage(\SotbitOrigami::moduleId.'_PAGINATION_ORIGAMI'),
            'origami_more' => Loc::getMessage(\SotbitOrigami::moduleId.'_PAGINATION_ORIGAMI_MORE'),
            'origami_both' => Loc::getMessage(\SotbitOrigami::moduleId.'_PAGINATION_ORIGAMI_BOTH'),
            'origami_auto' => Loc::getMessage(\SotbitOrigami::moduleId.'_PAGINATION_ORIGAMI_AUTO'),
        ];
    }

    /**
     * @return array
     */
    public static function getDetailTemplates()
    {
        return [
            '' => Loc::getMessage(\SotbitOrigami::moduleId.'_DETAIL_TEMPLATE_BASE'),
            'NO_TABS' => Loc::getMessage(\SotbitOrigami::moduleId.'_DETAIL_TEMPLATE_NO_TABS'),
        ];
    }

    /**
     * @return array
     */
    public static function getMainDetailTemplate()
    {
        return [
            '' => Loc::getMessage(\SotbitOrigami::moduleId.'_DETAIL_TEMPLATE_BASE'),
        ];
    }

    /**
     * @return array
     */
    public static function getTabs()
    {
        $return = [
            'DESCRIPTION',
            'PROPERTIES',
            'DELIVERY',
            'AVAILABLE',
            'COMMENTS',
            'VIDEO',
            'DOCS'
        ];
		foreach($return as $k => $r){
			if($r == 'DELIVERY' && !file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/kit/regions.delivery/class.php')){
				unset($return[$k]);
			}
		}
		return $return;
    }

    /**
     * @return array
     */
    public static function getResizeTypes()
    {
        return [
            "" => Loc::getMessage(\SotbitOrigami::moduleId.'_BX_RESIZE_IMAGE_NO'),
            BX_RESIZE_IMAGE_EXACT => Loc::getMessage(\SotbitOrigami::moduleId.'_BX_RESIZE_IMAGE_EXACT'),
            BX_RESIZE_IMAGE_PROPORTIONAL => Loc::getMessage(\SotbitOrigami::moduleId.'_BX_RESIZE_IMAGE_PROPORTIONAL'),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT => Loc::getMessage(\SotbitOrigami::moduleId.'_BX_RESIZE_IMAGE_PROPORTIONAL_ALT'),
        ];
    }

    /**
     * @return array
     */
    public static function getSkuDisplayTypes()
    {
        return [
            'ENUMERATION' => Loc::getMessage(\SotbitOrigami::moduleId.'_SKU_DISPLAY_TYPE_ENUMERATION'),
            'LIST_OF_MODIFICATIONS' => Loc::getMessage(\SotbitOrigami::moduleId.'_SKU_DISPLAY_TYPE_LIST_OF_MODIFICATIONS'),
            'COMBINED' => Loc::getMessage(\SotbitOrigami::moduleId.'_SKU_DISPLAY_TYPE_COMBINED'),
        ];
    }

    /**
     * @return array
     */
    public static function getDetailPictureDisplayTypes()
    {
        return [
            'popup' => Loc::getMessage(\SotbitOrigami::moduleId.'_DETAIL_PICTURE_DISPLAY_TYPE_POPUP'),
            'magnifier' => Loc::getMessage(\SotbitOrigami::moduleId.'_DETAIL_PICTURE_DISPLAY_TYPE_MAGNIFIER'),
        ];
    }

    /**
     * @return array
     */
    public static function getDropdownSideMenuViews()
    {
        return [
            'SIDE' => Loc::getMessage(\SotbitOrigami::moduleId.'_DROPDOWN_SIDE_MENU_VIEW_SIDE'),
            'BOTTOM' => Loc::getMessage(\SotbitOrigami::moduleId.'_DROPDOWN_SIDE_MENU_VIEW_BOTTOM'),
        ];
    }

     public static function getSectionDescription()
    {
        return [
            'ABOVE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_ABOVE'),
            'BELOW' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_BELOW'),
            'BOTH' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_BOTH'),
            'HIDE' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_HIDE'),
        ];
    }

    public static function getSectionDescriptionPosition()
    {
        return [
            'SECTION_DESC' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_SECTION'),
            'UF_DESCR_BOTTOM'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_UF_DESCR_BOTTOM'),
        ];
    }

    public static function getDescriptionPosition()
    {
        return [
            /*'RATHER' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_POSITION_RATHER'),*/
            'RATHER_ALL' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_POSITION_RATHER_ALL'),
            'UNDER'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_DESCRIPTION_POSITION_UNDER'),
        ];
    }

    public static function getTagTemlate()
    {
        return [
            'ROW'   => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TAG_ROW'),
            'COLUMN' => Loc::getMessage(\SotbitOrigami::moduleId
                .'_TAG_COLUMN'),
        ];
    }

    public static function getShowStockMode()
    {
        return [
            'N' => Loc::getMessage(\SotbitOrigami::moduleId .'_SHOW_STOCK_MODE_N'),
            'Y' => Loc::getMessage(\SotbitOrigami::moduleId .'_SHOW_STOCK_MODE_Y'),
            'M' => Loc::getMessage(\SotbitOrigami::moduleId .'_SHOW_STOCK_MODE_M'),
        ];
    }

    public static function getPropertyGrouperType()
    {
        return [
            'NO' => Loc::getMessage(\SotbitOrigami::moduleId .'_PROPERTY_GROUPER_NO'),
            'GRUPPER' => Loc::getMessage(\SotbitOrigami::moduleId .'_PROPERTY_GROUPER_GRUPPER'),
            'WEBDEBUG' => Loc::getMessage(\SotbitOrigami::moduleId .'_PROPERTY_GROUPER_WEBDEBUG'),
        ];
    }

    public static function getUserGroups()
    {
        $arUserGroups = [];
        $rsGroups = \CGroup::GetList($by = "id", $order = "asc", array("ACTIVE" => "Y"));
        while($arItem = $rsGroups->Fetch())
        {
            $arUserGroups[$arItem["ID"]] = $arItem["NAME"];
        }

        return $arUserGroups;
    }

    public static function getImgOffer(){
        return [
            'PRODUCT' => Loc::getMessage(\SotbitOrigami::moduleId.'_PRODUCT'),
            'OFFER' => Loc::getMessage(\SotbitOrigami::moduleId.'_OFFER'),
        ];
    }

    public function getOfferNames(){
        return [
            'OFFER_NAME' => Loc::getMessage(\SotbitOrigami::moduleId.'_OFFER_NAME'),
            'PRODUCT_NAME' => Loc::getMessage(\SotbitOrigami::moduleId.'_PRODUCT_NAME'),
            'PRODUCT_OFFERS_NAME' => Loc::getMessage(\SotbitOrigami::moduleId.'_PRODUCT_OFFERS_NAME'),
            'PRODUCT_PROPS_NAME' => Loc::getMessage(\SotbitOrigami::moduleId.'_PRODUCT_PROPS_NAME'),
        ];
    }
    public static function getTagsPosition()
    {
        return
        [
            'TOP' => Loc::getMessage(\SotbitOrigami::moduleId .'_TAGS_POSITION_TOP'),
            'BOTTOM' => Loc::getMessage(\SotbitOrigami::moduleId .'_TAGS_POSITION_BOTTOM'),
        ];
    }

    public static function getOfferLanding404()
    {
        return
            [
                '404' => Loc::getMessage(\SotbitOrigami::moduleId .'_OFFER_LANDING_404_404'),
                'ELEMENT' => Loc::getMessage(\SotbitOrigami::moduleId .'_OFFER_LANDING_404_ELEMENT'),
                'OFFER' => Loc::getMessage(\SotbitOrigami::moduleId .'_OFFER_LANDING_404_OFFER'),
                'IGNORE' => Loc::getMessage(\SotbitOrigami::moduleId .'_OFFER_LANDING_404_IGNORE'),
            ];
    }

    public static function getSeoDescription()
    {
        return
            [
                'NOT_HIDE' => Loc::getMessage(\SotbitOrigami::moduleId .'_NOT_HIDE'),
                'ANY_FILTERED_PAGE' => Loc::getMessage(\SotbitOrigami::moduleId .'_ANY_FILTERED_PAGE'),
                'HIDE_IF_RULE_EXIST' => Loc::getMessage(\SotbitOrigami::moduleId .'_HIDE_IF_RULE_EXIST'),
            ];
    }

    public static function getInlineCssAdmin()
    {
        return 'Y';
    }

    public static function getPaymentProps()
    {
        $return = [];

        /* Get site ID from GET params */
        $request = Context::getCurrent()->getRequest();
        $site_id = $request->getQuery('site');

        $rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
            [
                'select' => [
                    'ID',
                    'PERSON_TYPE_ID',
                    'NAME',
                ],
                'filter' => [
                    'PERSON_TYPE_ID' => self::get('PERSON_TYPE', $site_id),
                ],
            ]
        );
        while ($prop = $rs->fetch()) {
            $return[$prop['ID']] = '['.$prop['ID'].']['.$prop['PERSON_TYPE_ID']
                .'] '.$prop['NAME'];
        }

        return $return;
    }

    /**
     * Returns a list of order statuses
     *
     * @return mixed
     */
    public static function getStatusOrder()
    {
        $orderStatus = \CSaleStatus::GetList();

        while($arStatus = $orderStatus->Fetch())
        {
            $arSt[$arStatus["ID"]] = $arStatus["NAME"];
        }

        return $arSt;
    }

    /**
     * Get list displayed and required One Click form fields
     *
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getOCDisplayedRequiredFields()
    {
        $return = [];

        /* Get site ID from GET params */
        $request = Context::getCurrent()->getRequest();
        $site_id = $request->getQuery('site');

        $rs = \Bitrix\Sale\Internals\OrderPropsTable::getList(
            [
                'select' => [
                    'ID',
                    'PERSON_TYPE_ID',
                    'CODE',
                ],
                'filter' => [
                    'PERSON_TYPE_ID' => self::get('PERSON_TYPE', $site_id),
                ],
            ]
        );

        while ($prop = $rs->fetch()) {
            $return[$prop['CODE']] = $prop['CODE'];
        }

        $return['COMMENT'] = 'COMMENT';

        return $return;
    }
}
