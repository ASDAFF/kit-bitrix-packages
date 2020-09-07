<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\FileHelper;
use intec\constructor\Module as Constructor;
use intec\constructor\models\Build;
use intec\constructor\models\build\File;
use intec\constructor\models\Font;

/**
 * @var Build $this
 */

Loc::loadMessages(__FILE__);

$oFonts = Font::findAvailable()->indexBy('code');
$oFont = $oFonts->getFirst();

$meta = [
    'solution' => 'universe',
    'files' => [
        ['path' => 'css/areas.css', 'type' => File::TYPE_CSS],
        ['path' => 'css/common.css', 'type' => File::TYPE_CSS], // Deprecated
        ['path' => 'css/content.css', 'type' => File::TYPE_CSS],
        ['path' => 'css/template.css', 'type' => File::TYPE_CSS],
        ['path' => 'css/buttons.css', 'type' => File::TYPE_CSS], // Deprecated
        ['path' => 'css/controls.css', 'type' => File::TYPE_CSS], // Deprecated
        ['path' => 'css/interface.css', 'type' => File::TYPE_CSS],
        ['path' => 'css/grid.css', 'type' => File::TYPE_CSS],
        ['path' => 'css/scheme.scss', 'type' => File::TYPE_SCSS],
        ['path' => 'css/elements.scss', 'type' => File::TYPE_SCSS],

        ['path' => 'icons/fontawesome/style.css', 'type' => File::TYPE_CSS],
        ['path' => 'icons/typicons/style.css', 'type' => File::TYPE_CSS],
        ['path' => 'icons/glyphter/style.css', 'type' => File::TYPE_CSS],
        ['path' => 'icons/intec/style.css', 'type' => File::TYPE_CSS],

        ['path' => 'plugins/jquery.lazyLoad/jquery.lazyLoad.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.owlCarousel/jquery.owlCarousel.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.owlCarousel/jquery.owlCarousel.navigation.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.owlCarousel/jquery.owlCarousel.css', 'type' => File::TYPE_CSS],
        ['path' => 'plugins/jquery.owlCarousel/jquery.owlCarousel-theme.css', 'type' => File::TYPE_CSS],
        ['path' => 'plugins/jquery.animateNumber/jquery.animateNumber.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/picturefill/picturefill.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.lightGallery/jquery.lightGallery.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.lightGallery/jquery.lightGallery.css', 'type' => File::TYPE_CSS],
        ['path' => 'plugins/jquery.scrollbar/jquery.scrollbar.js', 'type' => File::TYPE_JAVASCRIPT],
        ['path' => 'plugins/jquery.scrollbar/jquery.scrollbar.css', 'type' => File::TYPE_CSS],
        ['path' => 'plugins/jquery.printThis/jquery.printThis.js', 'type' => File::TYPE_JAVASCRIPT],
    ],
    'properties-categories' => [
        'base' => ['name' => Loc::getMessage('template.meta.properties-categories.base')],
        'main' => ['name' => Loc::getMessage('template.meta.properties-categories.main')],
        'header' => ['name' => Loc::getMessage('template.meta.properties-categories.header')],
        'catalog' => ['name' => Loc::getMessage('template.meta.properties-categories.catalog')],
        'services' => ['name' => Loc::getMessage('template.meta.properties-categories.services')],
        'basket' => ['name' => Loc::getMessage('template.meta.properties-categories.basket')],
        'sections' => ['name' => Loc::getMessage('template.meta.properties-categories.sections')],
        'footer' => ['name' => Loc::getMessage('template.meta.properties-categories.footer')],
        'mobile' => ['name' => Loc::getMessage('template.meta.properties-categories.mobile')]
    ],
    'properties' => [
        'template-color' => [
            'name' => Loc::getMessage('template.meta.properties.template-color'),
            'type' => 'color',
            'category' => 'base',
            'default' => '#13181f',
            'values' => [
                '#69102f', '#e05615', '#383b47',
                '#074d90', '#d03349', '#1e8988',
                '#5bcab2', '#352ca6', '#f78e16',
                '#8dc6c7', '#772056', '#838ed9',
                '#143a52', '#81ae64', '#ff6f3c',
                '#f5b553', '#388e3c', '#44558f',
                '#2bb3c0', '#303481', '#0065ff',
                '#3498db', '#c50000'
            ]
        ],
        'template-background-show' => [
            'name' => Loc::getMessage('template.meta.properties.template-background-show'),
            'type' => 'boolean',
            'category' => 'base',
            'default' => false
        ],
        'template-background-color' => [
            'name' => Loc::getMessage('template.meta.properties.template-background-color'),
            'type' => 'color',
            'category' => 'base',
            'default' => '#c8c8cd',
            'values' => [
                '#fff', '#c8c8cd'
            ]
        ],
        'template-font' => [
            'name' => Loc::getMessage('template.meta.properties.template-font'),
            'type' => 'list',
            'category' => 'base',
            'default' => !empty($oFont) ? $oFont->code : null,
            'values' => $oFonts->asArray(function ($sCode, $oFont) {
                /** @var Font $oFont */

                return [
                    'value' => [
                        'name' => $oFont->name,
                        'value' => $sCode
                    ]
                ];
            })
        ],
        'template-images-effect' => [
            'name' => Loc::getMessage('template.meta.properties.template-images-effect'),
            'type' => 'list',
            'category' => 'base',
            'default' => 'flash',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.template-images-effect.none'),
                'value' => 'none'
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-images-effect.flash'),
                'value' => 'flash'
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-images-effect.circle'),
                'value' => 'circle'
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-images-effect.opacity'),
                'value' => 'opacity'
            ]]
        ],
        'template-images-lazyload-use' => [
            'name' => Loc::getMessage('template.meta.properties.template-images-lazyload-use'),
            'type' => 'boolean',
            'category' => 'base',
            'default' => true
        ],
        'template-titles-size' => [
            'name' => Loc::getMessage('template.meta.properties.template-titles-size'),
            'type' => 'list',
            'category' => 'base',
            'default' => 24,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.template-titles-size.20'),
                'value' => 20
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-titles-size.24'),
                'value' => 24
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-titles-size.30'),
                'value' => 30
            ]]
        ],
        'template-text-size' => [
            'name' => Loc::getMessage('template.meta.properties.template-text-size'),
            'type' => 'list',
            'category' => 'base',
            'default' => 14,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.template-text-size.13'),
                'value' => 13
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-text-size.14'),
                'value' => 14
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-text-size.15'),
                'value' => 15
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-text-size.16'),
                'value' => 16
            ]]
        ],
        'template-width' => [
            'name' => Loc::getMessage('template.meta.properties.template-width'),
            'type' => 'list',
            'category' => 'base',
            'default' => 1200,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.template-width.1200'),
                'value' => 1200
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-width.1344'),
                'value' => 1344
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-width.1500'),
                'value' => 1500
            ], [
                'name' => Loc::getMessage('template.meta.properties.template-width.1700'),
                'value' => 1700
            ]]
        ],
        'template-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.template-menu-show'),
            'type' => 'boolean',
            'category' => 'base',
            'default' => true
        ],
        'template-cache' => [
            'name' => Loc::getMessage('template.meta.properties.template-cache'),
            'type' => 'boolean',
            'category' => 'base',
            'visible' => !Constructor::isLite(),
            'default' => true,
        ],
        'base-regionality-use' => [
            'name' => Loc::getMessage('template.meta.properties.base-regionality-use'),
            'type' => 'boolean',
            'category' => 'base',
            'visible' => Loader::includeModule('intec.regionality'),
            'default' => true,
        ],
        'base-consent' => [
            'name' => Loc::getMessage('template.meta.properties.base-consent'),
            'type' => 'boolean',
            'category' => 'base',
            'default' => true,
        ],
        'base-map-vendor' => [
            'name' => Loc::getMessage('template.meta.properties.base-map-vendor'),
            'type' => 'list',
            'category' => 'base',
            'default' => 'yandex',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.base-map-vendor.yandex'),
                'value' => 'yandex'
            ], [
                'name' => Loc::getMessage('template.meta.properties.base-map-vendor.google'),
                'value' => 'google'
            ]]
        ],
        'base-search-mode' => [
            'name' => Loc::getMessage('universe.meta.properties.base-search-mode'),
            'type' => 'list',
            'category' => 'base',
            'default' => 'site',
            'values' => [[
                'name' => Loc::getMessage('universe.meta.properties.base-search-mode.site'),
                'value' => 'site'
            ], [
                'name' => Loc::getMessage('universe.meta.properties.base-search-mode.catalog'),
                'value' => 'catalog'
            ]]
        ],
        'basket-use' => [
            'name' => Loc::getMessage('template.meta.properties.basket-use'),
            'type' => 'boolean',
            'category' => 'basket',
            'default' => true,
        ],
        'basket-delay-use' => [
            'name' => Loc::getMessage('template.meta.properties.basket-delay-use'),
            'type' => 'boolean',
            'category' => 'basket',
            'default' => true,
        ],
        'basket-compare-use' => [
            'name' => Loc::getMessage('template.meta.properties.basket-compare-use'),
            'type' => 'boolean',
            'category' => 'basket',
            'default' => true,
        ],
        'basket-position' => [
            'name' => Loc::getMessage('template.meta.properties.basket-position'),
            'type' => 'list',
            'category' => 'basket',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.basket-position.header'),
                'value' => 'header'
            ], [
                'name' => Loc::getMessage('template.meta.properties.basket-position.fixed.right'),
                'value' => 'fixed.right'
            ]],
            'default' => 'header'
        ],
        'basket-fixed-template' => [
            'name' => Loc::getMessage('template.meta.properties.basket-fixed-template'),
            'type' => 'list',
            'category' => 'basket',
            'values' => [[
                'name' => Loc::getMessage('universe.meta.properties.basket-fixed-template.template.1'),
                'value' => 'template.1'
            ], [
                'name' => Loc::getMessage('universe.meta.properties.basket-fixed-template.template.2'),
                'value' => 'template.2'
            ]],
            'default' => 'template.1'
        ],
        'basket-fixed-auto' => [
            'name' => Loc::getMessage('template.meta.properties.basket-fixed-auto'),
            'type' => 'boolean',
            'category' => 'basket',
            'default' => true
        ],
        'basket-notifications-use' => [
            'name' => Loc::getMessage('template.meta.properties.basket-notifications-use'),
            'type' => 'boolean',
            'category' => 'basket',
            'default' => false
        ],
        'catalog-detail-gallery-modes' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-gallery-modes'),
            'type' => 'list',
            'multiple' => true,
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-gallery-modes.zoom'),
                'value' => 'zoom'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-gallery-modes.popup'),
                'value' => 'popup'
            ]],
            'default' => [
                'zoom',
                'popup'
            ]
        ],
        'catalog-detail-sku-view' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-sku-view'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-sku-view.dynamic'),
                'value' => 'dynamic'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-sku-view.list'),
                'value' => 'list'
            ]],
            'default' => 'dynamic'
        ],
        'catalog-products-view-mode' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-products-view-mode'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-products-view-mode.text'),
                'value' => 'text'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-products-view-mode.list'),
                'value' => 'list'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-products-view-mode.tile'),
                'value' => 'tile'
            ]],
            'default' => 'tile'
        ],
        'catalog-menu-view' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-menu-view'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-menu-view.simple.1'),
                'value' => 'simple.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-menu-view.pictures.1'),
                'value' => 'pictures.1'
            ]],
            'default' => 'simple.1'
        ],
        'catalog-quick-view-use' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-quick-view-use'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => true
        ],
        'catalog-quick-view-detail' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-quick-view-detail'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => false
        ],
        'catalog-detail-panel-show' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-panel-show'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => true
        ],
        'catalog-detail-panel-mobile-show' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-panel-mobile-show'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => true
        ],
        'catalog-root-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-root-menu-show'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => false
        ],
        'catalog-sections-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-sections-menu-show'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => true
        ],
        'catalog-detail-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-menu-show'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => false
        ],
        'catalog-root-layout' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-root-layout'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-root-layout.1'),
                'value' => '1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-root-layout.2'),
                'value' => '2'
            ]],
            'default' => '1'
        ],
        'catalog-root-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-root-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-root-template.tile.1'),
                'value' => 'tile.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-root-template.list.1'),
                'value' => 'list.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-root-template.tile.2'),
                'value' => 'tile.2'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-root-template.tile.3'),
                'value' => 'tile.3'
            ]],
            'default' => 'tile.1'
        ],
        'catalog-sections-layout' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-sections-layout'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-sections-layout.1'),
                'value' => '1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-sections-layout.2'),
                'value' => '2'
            ]],
            'default' => '1'
        ],
        'catalog-sections-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-sections-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-sections-template.tile.1'),
                'value' => 'tile.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-root-template.list.1'),
                'value' => 'list.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-sections-template.tile.2'),
                'value' => 'tile.2'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-sections-template.tile.3'),
                'value' => 'tile.3'
            ]],
            'default' => 'tile.1'
        ],
        'catalog-filter-ajax' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-filter-ajax'),
            'type' => 'boolean',
            'category' => 'catalog',
            'default' => false
        ],
        'catalog-filter-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-filter-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-filter-template.vertical.1'),
                'value' => 'vertical.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-filter-template.vertical.2'),
                'value' => 'vertical.2'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-filter-template.horizontal.1'),
                'value' => 'horizontal.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-filter-template.horizontal.2'),
                'value' => 'horizontal.2'
            ]],
            'default' => 'vertical.1'
        ],
        'catalog-elements-text-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-elements-text-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-text-template.text.1'),
                'value' => 'text.1'
            ]],
            'default' => 'text.1'
        ],
        'catalog-elements-list-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-elements-list-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-list-template.list.1'),
                'value' => 'list.1'
            ]],
            'default' => 'list.1'
        ],
        'catalog-elements-tile-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.1'),
                'value' => 'tile.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.1.columns.4'),
                'value' => 'tile.1.columns.4'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.2'),
                'value' => 'tile.2'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.2.columns.4'),
                'value' => 'tile.2.columns.4'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.3'),
                'value' => 'tile.3'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.4'),
                'value' => 'tile.4'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-template.tile.4.columns.4'),
                'value' => 'tile.4.columns.4'
            ]],
            'default' => 'tile.1'
        ],
        'catalog-elements-tile-mobile-columns' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-mobile-columns'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-mobile-columns.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-elements-tile-mobile-columns.2'),
                'value' => 2
            ]],
            'default' => 2
        ],
        'catalog-detail-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-detail-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.1.wide'),
                'value' => 'default.1.wide'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.1.tabs.top'),
                'value' => 'default.1.tabs.top'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.1.tabs.right'),
                'value' => 'default.1.tabs.right'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.2.wide'),
                'value' => 'default.2.wide'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.2.narrow'),
                'value' => 'default.2.narrow'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.2.tabs.top'),
                'value' => 'default.2.tabs.top'
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-detail-template.default.3.wide'),
                'value' => 'default.3.wide'
            ]],
            'default' => 'default.1.wide'
        ],
        'catalog-quick-view-template' => [
            'name' => Loc::getMessage('template.meta.properties.catalog-quick-view-template'),
            'type' => 'list',
            'category' => 'catalog',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.catalog-quick-view-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.catalog-quick-view-template.2'),
                'value' => 2
            ]],
            'default' => 1
        ],
        'footer-blocks' => [
            'name' => Loc::getMessage('template.meta.properties.footer-blocks'),
            'type' => 'blocks',
            'category' => 'footer',
            'blocks' => [
                'form' => [
                    'name' => Loc::getMessage('template.meta.properties.footer-blocks.form'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.footer-blocks.form.wide.1'),
                        'value' => 'wide.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.footer-blocks.form.wide.2'),
                        'value' => 'wide.2'
                    ]]
                ],
                'contacts' => [
                    'name' => Loc::getMessage('template.meta.properties.footer-blocks.contacts'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.footer-blocks.contacts.wide.1'),
                        'value' => 'wide.1'
                    ]]
                ]
            ]
        ],
        'footer-theme' => [
            'name' => Loc::getMessage('template.meta.properties.footer-theme'),
            'type' => 'list',
            'category' => 'footer',
            'default' => 'light',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.footer-theme.light'),
                'value' => 'light'
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-theme.dark'),
                'value' => 'dark'
            ]]
        ],
        'footer-products-viewed-show' => [
            'name' => Loc::getMessage('template.meta.properties.footer-products-viewed-show'),
            'type' => 'boolean',
            'category' => 'footer',
            'default' => false
        ],
        'footer-template' => [
            'name' => Loc::getMessage('template.meta.properties.footer-template'),
            'type' => 'list',
            'category' => 'footer',
            'default' => 1,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.footer-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-template.4'),
                'value' => 4
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-template.5'),
                'value' => 5
            ], [
                'name' => Loc::getMessage('template.meta.properties.footer-template.6'),
                'value' => 6
            ]]
        ],
        'header-basket-popup-show' => [
            'name' => Loc::getMessage('template.meta.properties.header-basket-popup-show'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-template' => [
            'name' => Loc::getMessage('template.meta.properties.header-template'),
            'type' => 'list',
            'category' => 'header',
            'default' => 1,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.header-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.4'),
                'value' => 4
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.5'),
                'value' => 5
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.6'),
                'value' => 6
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.7'),
                'value' => 7
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.8'),
                'value' => 8
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.9'),
                'value' => 9
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.10'),
                'value' => 11
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.11'),
                'value' => 11
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.12'),
                'value' => 12
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.13'),
                'value' => 13
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.14'),
                'value' => 14
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.15'),
                'value' => 15
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.16'),
                'value' => 16
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.17'),
                'value' => 17
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.18'),
                'value' => 18
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-template.19'),
                'value' => 19
            ]]
        ],
        'header-menu-uppercase-use' => [
            'name' => Loc::getMessage('template.meta.properties.header-menu-uppercase-use'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => false
        ],
        'header-menu-overlay-use' => [
            'name' => Loc::getMessage('template.meta.properties.header-menu-overlay-use'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-fixed-use' => [
            'name' => Loc::getMessage('template.meta.properties.header-fixed-use'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-fixed-menu-popup-show' => [
            'name' => Loc::getMessage('template.meta.properties.header-fixed-menu-popup-show'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-menu-popup-template' => [
            'name' => Loc::getMessage('template.meta.properties.header-menu-popup-template'),
            'type' => 'list',
            'category' => 'header',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.header-menu-popup-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-menu-popup-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-menu-popup-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-menu-popup-template.4'),
                'value' => 4
            ]],
            'default' => 1
        ],
        'header-mobile-template' => [
            'name' => Loc::getMessage('template.meta.properties.header-mobile-template'),
            'type' => 'list',
            'category' => 'header',
            'default' => 'white',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.header-mobile-template.white'),
                'value' => 'white'
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-template.colored'),
                'value' => 'colored'
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-template.white-with-icons'),
                'value' => 'white-with-icons'
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-template.colored-with-icons'),
                'value' => 'colored-with-icons'
            ]]
        ],
        'header-mobile-fixed' => [
            'name' => Loc::getMessage('template.meta.properties.header-mobile-fixed'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-mobile-hidden' => [
            'name' => Loc::getMessage('template.meta.properties.header-mobile-hidden'),
            'type' => 'boolean',
            'category' => 'header',
            'default' => true
        ],
        'header-mobile-search-type' => [
            'name' => Loc::getMessage('template.meta.properties.header-mobile-search-type'),
            'type' => 'list',
            'category' => 'header',
            'default' => 'popup',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.header-mobile-search-type.page'),
                'value' => 'page'
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-search-type.popup'),
                'value' => 'popup'
            ]]
        ],
        'header-mobile-menu-template' => [
            'name' => Loc::getMessage('template.meta.properties.header-mobile-menu-template'),
            'type' => 'list',
            'category' => 'header',
            'default' => 1,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.header-mobile-menu-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-menu-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-menu-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.header-mobile-menu-template.4'),
                'value' => 4
            ]]
        ],
        'pages-main-template' => [
            'name' => Loc::getMessage('template.meta.properties.pages-main-template'),
            'type' => 'list',
            'category' => 'main',
            'default' => 'wide',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.pages-main-template.wide'),
                'value' => 'wide'
            ], [
                'name' => Loc::getMessage('template.meta.properties.pages-main-template.narrow.left'),
                'value' => 'narrow.left'
            ]]
        ],
        'pages-main-blocks' => [
            'name' => Loc::getMessage('template.meta.properties.pages-main-blocks'),
            'type' => 'blocks',
            'category' => 'main',
            'blocks' => [
                'banner' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.1'),
                        'value' => 1
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.2'),
                        'value' => 2
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.3'),
                        'value' => 3
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.4'),
                        'value' => 4
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.5'),
                        'value' => 5
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.6'),
                        'value' => 6
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.7'),
                        'value' => 7
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.8'),
                        'value' => 8
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.9'),
                        'value' => 9
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.10'),
                        'value' => 10
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.11'),
                        'value' => 11
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.banner.12'),
                        'value' => 12
                    ]]
                ],
                'icons' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.icons')
                ],
                'sections' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.sections'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.sections.list.1'),
                        'value' => 'list.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.sections.tiles.1'),
                        'value' => 'tiles.1'
                    ]]
                ],
                'categories' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.chess.1'),
                        'value' => 'chess.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.mosaic.1'),
                        'value' => 'mosaic.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.2'),
                        'value' => 'tiles.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.list.1'),
                        'value' => 'list.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.3'),
                        'value' => 'tiles.3'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.4'),
                        'value' => 'tiles.4'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.5'),
                        'value' => 'tiles.5'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.chess.2'),
                        'value' => 'chess.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.categories.tiles.6'),
                        'value' => 'tiles.6'
                    ]]
                ],
                'gallery' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.gallery'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.gallery.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.gallery.tiles.2'),
                        'value' => 'tiles.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.gallery.tiles.3'),
                        'value' => 'tiles.3'
                    ]]
                ],
                'products' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.products'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.products.slider.1'),
                        'value' => 'slider.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.products.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.products.tiles.2'),
                        'value' => 'tiles.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.products.tiles.3'),
                        'value' => 'tiles.3'
                    ]]
                ],
                'reviews' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.list.1'),
                        'value' => 'list.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.slider.1'),
                        'value' => 'slider.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.blocks.1'),
                        'value' => 'blocks.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.blocks.2'),
                        'value' => 'blocks.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.list.2'),
                        'value' => 'list.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.slider.2'),
                        'value' => 'slider.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.slider.3'),
                        'value' => 'slider.3'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.reviews.slider.4'),
                        'value' => 'slider.4'
                    ]]
                ],
                'services' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.blocks.1'),
                        'value' => 'blocks.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.2'),
                        'value' => 'tiles.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.3'),
                        'value' => 'tiles.3'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.blocks.2'),
                        'value' => 'blocks.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.4'),
                        'value' => 'tiles.4'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.5'),
                        'value' => 'tiles.5'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.6'),
                        'value' => 'tiles.6'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.wide.1'),
                        'value' => 'wide.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.services.tiles.7'),
                        'value' => 'tiles.7'
                    ]]
                ],
                'video' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.video'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.video.wide.1'),
                        'value' => 'wide.1'
                    ]]
                ],
                'advantages' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.advantages'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.advantages.icons.1'),
                        'value' => 'icons.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.advantages.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.advantages.chess.1'),
                        'value' => 'chess.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.advantages.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'projects' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.projects'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.projects.tiles.1'),
                        'value' => 'tiles.1'
                    ]]
                ],
                'staff' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.staff'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.staff.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.staff.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'solutions' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.solutions'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.solutions.slider.1'),
                        'value' => 'slider.1'
                    ]]
                ],
                'faq' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.faq'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.faq.wide.1'),
                        'value' => 'wide.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.faq.wide.2'),
                        'value' => 'wide.2'
                    ]]
                ],
                'stages' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.stages'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.stages.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.stages.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'certificates' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.certificates'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.certificates.tiles.1'),
                        'value' => 'tiles.1'
                    ]]
                ],
                'news' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.news'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.news.blocks.1'),
                        'value' => 'blocks.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.news.blocks.2'),
                        'value' => 'blocks.2'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.news.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.news.slider.1'),
                        'value' => 'slider.1'
                    ]]
                ],
                'shares' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.shares'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.shares.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.shares.blocks.1'),
                        'value' => 'blocks.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.shares.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'about' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.about')
                ],
                'brands' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.brands'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.brands.slider.1'),
                        'value' => 'slider.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.brands.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.brands.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'articles' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.articles'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.articles.tiles.1'),
                        'value' => 'tiles.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.articles.tiles.2'),
                        'value' => 'tiles.2'
                    ]]
                ],
                'rates' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.rates'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.rates.tiles.1'),
                        'value' => 'tiles.1'
                    ]]
                ],
                'videos' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.videos'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.videos.slider.1'),
                        'value' => 'slider.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.videos.list.1'),
                        'value' => 'list.1'
                    ]]
                ],
                'contacts' => [
                    'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.contacts'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.contacts.simple.1'),
                        'value' => 'simple.1'
                    ], [
                        'name' => Loc::getMessage('template.meta.properties.pages-main-blocks.contacts.list.1'),
                        'value' => 'list.1'
                    ]]
                ]
            ]
        ],
        'sections-shares-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-shares-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'list.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-shares-template.blocks.1'),
                'value' => 'blocks.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-shares-template.blocks.2'),
                'value' => 'blocks.2'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-shares-template.list.1'),
                'value' => 'list.1'
            ]]
        ],
        'sections-contacts-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-contacts-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'simple.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-contacts-template.simple.1'),
                'value' => 'simple.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-contacts-template.shops.1'),
                'value' => 'shops.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-contacts-template.offices.1'),
                'value' => 'offices.1'
            ]]
        ],
        'sections-news-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-news-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'list.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-news-template.blocks.1'),
                'value' => 'blocks.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-news-template.list.1'),
                'value' => 'list.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-news-template.tiles.1'),
                'value' => 'tiles.1'
            ]]
        ],
        'sections-articles-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-articles-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'list.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-articles-template.blocks.1'),
                'value' => 'blocks.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-articles-template.list.1'),
                'value' => 'list.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-articles-template.tiles.1'),
                'value' => 'tiles.1'
            ]]
        ],
        'sections-blog-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-blog-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'list.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-blog-template.blocks.1'),
                'value' => 'blocks.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-blog-template.list.1'),
                'value' => 'list.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-blog-template.tiles.1'),
                'value' => 'tiles.1'
            ]]
        ],
        'sections-certificates-template' => [
            'name' => Loc::getMessage('template.meta.properties.sections-certificates-template'),
            'type' => 'list',
            'category' => 'sections',
            'default' => 'tiles.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.sections-certificates-template.tiles.1'),
                'value' => 'tiles.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.sections-certificates-template.list.1'),
                'value' => 'list.1'
            ]]
        ],
        'services-root-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.services-root-menu-show'),
            'type' => 'boolean',
            'category' => 'services',
            'default' => false
        ],
        'services-root-template' => [
            'name' => Loc::getMessage('template.meta.properties.services-root-template'),
            'type' => 'list',
            'category' => 'services',
            'default' => 1,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.services-root-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.4'),
                'value' => 4
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.5'),
                'value' => 5
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.6'),
                'value' => 6
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.7'),
                'value' => 7
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.8'),
                'value' => 8
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.9'),
                'value' => 9
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-root-template.10'),
                'value' => 10
            ]]
        ],
        'services-section-menu-show' => [
            'name' => Loc::getMessage('template.meta.properties.services-section-menu-show'),
            'type' => 'boolean',
            'category' => 'services',
            'default' => true
        ],
        'services-section-template' => [
            'name' => Loc::getMessage('template.meta.properties.services-section-template'),
            'type' => 'list',
            'category' => 'services',
            'default' => 1,
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.services-section-template.1'),
                'value' => 1
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.2'),
                'value' => 2
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.3'),
                'value' => 3
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.4'),
                'value' => 4
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.5'),
                'value' => 5
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.6'),
                'value' => 6
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.7'),
                'value' => 7
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.8'),
                'value' => 8
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.9'),
                'value' => 9
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-section-template.10'),
                'value' => 10
            ]]
        ],
        'services-element-template' => [
            'name' => Loc::getMessage('template.meta.properties.services-element-template'),
            'type' => 'list',
            'category' => 'services',
            'default' => 'wide.1',
            'values' => [[
                'name' => Loc::getMessage('template.meta.properties.services-element-template.narrow.1'),
                'value' => 'narrow.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-element-template.wide.1'),
                'value' => 'wide.1'
            ], [
                'name' => Loc::getMessage('template.meta.properties.services-element-template.narrow.2'),
                'value' => 'narrow.2'
            ]]
        ],
        'mobile-blocks' => [
            'name' => Loc::getMessage('template.meta.properties.mobile-blocks'),
            'type' => 'blocks',
            'category' => 'mobile',
            'blocks' => [
                'panel' => [
                    'name' => Loc::getMessage('template.meta.properties.mobile-blocks.panel'),
                    'templates' => [[
                        'name' => Loc::getMessage('template.meta.properties.mobile-blocks.panel.1'),
                        'value' => 'template.1'
                    ]]
                ]
            ]
        ],
        'mobile-panel-hidden' => [
            'name' => Loc::getMessage('template.meta.properties.mobile.panel.hidden'),
            'type' => 'boolean',
            'category' => 'mobile',
            'default' => true
        ]
    ]
];

/** CUSTOM START */

unset($meta['properties']['pages-main-blocks']['blocks']['solutions']);

/** CUSTOM END */

if (FileHelper::isFile($this->getDirectory().'/parts/custom/meta.php'))
    include($this->getDirectory().'/parts/custom/meta.php');

return $meta;