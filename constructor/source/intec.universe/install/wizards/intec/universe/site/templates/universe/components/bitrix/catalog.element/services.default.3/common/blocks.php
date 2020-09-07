<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

return [
    'banner' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_BANNER'),
        'TYPE' => 'sticky.both',
        'SORTABLE' => false
    ],
    'description.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_DESCRIPTION_1')
    ],
    'icons.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_ICONS_1')
    ],
    'form.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_FORM_1')
    ],
    'gallery.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_GALLERY_1')
    ],
    'properties.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_PROPERTIES_1')
    ],
    'documents.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_DOCUMENTS_1')
    ],
    'videos.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_VIDEOS_1')
    ],
    'projects.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_PROJECTS_1')
    ],
    'reviews.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_REVIEWS_1')
    ],
    'services.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_SERVICES_1')
    ],
    'products.1' => [
        'NAME' => Loc::getMessage('C_CATALOG_SERVICES_DEFAULT_1_COMMON_BLOCKS_PRODUCTS_1')
    ]
];