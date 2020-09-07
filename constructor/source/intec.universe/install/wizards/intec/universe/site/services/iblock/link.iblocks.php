<? include('.begin.php') ?>
<?

use intec\core\base\Collection;

/**
 * @var Collection $data
 * @var string $mode
 * @var CWizardBase $wizard
 * @var CWizardStep $this
 */

$macros = $data->get('macros');

/** CUSTOM START */

if ($mode == WIZARD_MODE_INSTALL) {
    /** Привязка услуг */
    if (!empty($macros['CATALOGS_SERVICES_IBLOCK_ID'])) {
        $arServices = [];
        $rsServices = CIBlockElement::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $macros['CATALOGS_SERVICES_IBLOCK_ID']
        ]);

        while ($arService = $rsServices->Fetch())
            $arServices[] = $arService;

        unset($rsServices, $arService);

        /** Привязка иконок */
        if (!empty($macros['CATALOGS_SERVICES_ICONS_IBLOCK_ID'])) {
            $arMap = [];
            $arIcons = [];
            $rsIcons = CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $macros['CATALOGS_SERVICES_ICONS_IBLOCK_ID']
            ]);

            while ($arIcon = $rsIcons->Fetch())
                $arIcons[] = $arIcon;

            if (!empty($arIcons))
                foreach ($arServices as $arService) {
                    $iCount = 0;

                    if (empty($arService['CODE']))
                        continue;

                    $arMap[$arService['CODE']] = [];

                    foreach ($arIcons as $arIcon) {
                        if (empty($arIcon['CODE']))
                            continue;

                        $arMap[$arService['CODE']][] = $arIcon['CODE'];
                        $iCount++;

                        if ($iCount >= 3)
                            break;
                    }
                }

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'ICONS_ELEMENTS', $macros['CATALOGS_SERVICES_ICONS_IBLOCK_ID'], $arMap);

            unset($arService, $rsIcons, $arIcons, $arIcon, $arMap);
        }

        /** Привязка галереи */
        if (!empty($macros['CATALOGS_SERVICES_GALLERY_IBLOCK_ID'])) {
            $arMap = [];
            $arPictures = [];
            $rsPictures = CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $macros['CATALOGS_SERVICES_GALLERY_IBLOCK_ID']
            ]);

            while ($arPicture = $rsPictures->Fetch())
                $arPictures[] = $arPicture;

            foreach ($arServices as $arService) {
                if (empty($arService['CODE']))
                    continue;

                $arMap[$arService['CODE']] = [];

                foreach ($arPictures as $arPicture) {
                    if (empty($arPicture['CODE']))
                        continue;

                    if (\intec\core\helpers\StringHelper::startsWith($arPicture['CODE'], $arService['CODE']))
                        $arMap[$arService['CODE']][] = $arPicture['CODE'];
                }
            }

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'GALLERY_ELEMENTS', $macros['CATALOGS_SERVICES_GALLERY_IBLOCK_ID'], $arMap);

            unset($arService, $rsPictures, $arPictures, $arPicture, $arMap);
        }

        /** Привязка видеогалереи */
        if (!empty($macros['CONTENT_VIDEO_IBLOCK_ID'])) {
            $arMap = [];
            $arVideos = [];
            $rsVideos = CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $macros['CONTENT_VIDEO_IBLOCK_ID'],
                'ACTIVE' => 'Y'
            ]);

            while ($arVideo = $rsVideos->Fetch())
                $arVideos[] = $arVideo;

            if (!empty($arVideos))
                foreach ($arServices as $arService) {
                    $iCount = 0;

                    if (empty($arService['CODE']))
                        continue;

                    $arMap[$arService['CODE']] = [];

                    foreach ($arVideos as $arVideo) {
                        if (empty($arVideo['CODE']))
                            continue;

                        $arMap[$arService['CODE']][] = $arVideo['CODE'];
                        $iCount++;

                        if ($iCount >= 3)
                            break;
                    }
                }

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'VIDEOS_ELEMENTS', $macros['CONTENT_VIDEO_IBLOCK_ID'], $arMap);

            unset($arService, $rsVideos, $arVideos, $arVideo, $arMap);
        }

        /** Привязка проектов */
        if (!empty($macros['CONTENT_PROJECTS_IBLOCK_ID'])) {
            $arMap = [
                'car_repair' => [
                    'project_5',
                    'project_6',
                    'project_7',
                    'project_8'
                ],
                'locksmith_repair' => [
                    'project_5',
                    'project_6',
                    'project_7',
                    'project_8'
                ],
                'installation_of_interior_doors' => [
                    'project_1',
                    'project_2',
                    'project_3',
                    'project_4'
                ],
                'windows_installation' => [
                    'project_1',
                    'project_2',
                    'project_3',
                    'project_4'
                ],
                'car_wash_service' => [
                    'project_5',
                    'project_6',
                    'project_7',
                    'project_8'
                ],
                'realtor_services' => [
                    'project_1',
                    'project_2',
                    'project_3',
                    'project_4'
                ],
                'sale_and_rent' => [
                    'project_1',
                    'project_2',
                    'project_3',
                    'project_4'
                ]
            ];

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'PROJECTS_ELEMENTS', $macros['CONTENT_PROJECTS_IBLOCK_ID'], $arMap);

            unset($arService, $rsProjects, $arProjects, $arProject, $arMap);
        }

        /** Привязка отзывов */
        if (!empty($macros['CATALOGS_SERVICES_REVIEWS_IBLOCK_ID'])) {
            $arMap = [];
            $arReviews = [];
            $rsReviews = CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $macros['CATALOGS_SERVICES_REVIEWS_IBLOCK_ID'],
                'ACTIVE' => 'Y'
            ]);

            while ($arReview = $rsReviews->Fetch())
                $arReviews[] = $arReview;

            if (!empty($arReviews))
                foreach ($arServices as $arService) {
                    $iCount = 0;

                    if (empty($arService['CODE']))
                        continue;

                    $arMap[$arService['CODE']] = [];

                    foreach ($arReviews as $arReview) {
                        if (empty($arReview['CODE']))
                            continue;

                        $arMap[$arService['CODE']][] = $arReview['CODE'];
                        $iCount++;

                        if ($iCount >= 3)
                            break;
                    }
                }

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'REVIEWS_ELEMENTS', $macros['CATALOGS_SERVICES_REVIEWS_IBLOCK_ID'], $arMap);

            unset($arService, $rsReviews, $arReviews, $arReview, $arMap);
        }

        /** Привязка сопутствующих услуг */
        $arMap = [];

        if (!empty($arServices)) {
            $arServicesChild = [];
            $iCount = 0;

            foreach ($arServices as $arService) {
                if (empty($arService['CODE']))
                    continue;

                $arServicesChild[] = $arService['CODE'];
                $iCount++;

                if ($iCount >= 4)
                    break;
            }

            foreach ($arServices as $arService) {
                if (empty($arService['CODE']))
                    continue;

                $arMap[$arService['CODE']] = $arServicesChild;
            }
        }

        $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'SERVICES_ELEMENTS', $macros['CATALOGS_SERVICES_IBLOCK_ID'], $arMap);

        unset($arService, $iCount, $arServicesChild, $arMap);

        if (!empty($macros['CATALOGS_PRODUCTS_IBLOCK_ID'])) {
            $arMap = [];
            $arProducts = [];
            $rsProducts = CIBlockElement::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $macros['CATALOGS_PRODUCTS_IBLOCK_ID'],
                'ACTIVE' => 'Y'
            ]);

            while ($arProduct = $rsProducts->Fetch())
                $arProducts[] = $arProduct;

            if (!empty($arProducts)) {
                $arProductsChild = [];
                $iCount = 0;

                foreach ($arProducts as $arProduct) {
                    if (empty($arProduct['CODE']))
                        continue;

                    $arProductsChild[] = $arProduct['CODE'];
                    $iCount++;

                    if ($iCount >= 4)
                        break;
                }

                foreach ($arServices as $arService) {
                    if (empty($arService['CODE']))
                        continue;

                    $arMap[$arService['CODE']] = $arProductsChild;
                }
            }

            $linkPropertyElements($macros['CATALOGS_SERVICES_IBLOCK_ID'], 'PRODUCTS_ELEMENTS', $macros['CATALOGS_PRODUCTS_IBLOCK_ID'], $arMap);

            unset($arService, $iCount, $arProduct, $arProducts, $arProductsChild, $arMap);
        }
    }

    /** Привязка акций */
    if (!empty($macros['CONTENT_SHARES_IBLOCK_ID'])) {
        $arShares = \intec\core\collections\Arrays::fromDBResult(CIBlockElement::GetList(['SORT' => 'ASC'], [
            'IBLOCK_ID' => $macros['CATALOGS_SHARES_IBLOCK_ID']
        ]));

        /** Привязка промо */
        if (!empty($macros['CONTENT_SHARES_PROMO_IBLOCK_ID'])) {
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'PROMO_ELEMENTS', $macros['CONTENT_SHARES_PROMO_IBLOCK_ID'], [
                'share_1' => [
                    'warranty',
                    'delivery'
                ],
                'share_2' => [
                    'warranty',
                    'delivery'
                ],
                'share_3' => [
                    'warranty',
                    'delivery'
                ],
                'share_4' => [
                    'warranty',
                    'delivery'
                ],
                'share_5' => [
                    'warranty',
                    'delivery'
                ],
                'share_6' => [
                    'warranty',
                    'delivery'
                ]
            ]);
        }

        /** Привязка условий */
        if (!empty($macros['CONTENT_SHARES_CONDITIONS_IBLOCK_ID'])) {
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'CONDITIONS_ELEMENTS', $macros['CONTENT_SHARES_CONDITIONS_IBLOCK_ID'], [
                'share_1' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ],
                'share_2' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ],
                'share_3' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ],
                'share_4' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ],
                'share_5' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ],
                'share_6' => [
                    'condition_1',
                    'condition_2',
                    'condition_3'
                ]
            ]);
        }

        /** Привязка видео */
        if (!empty($macros['CONTENT_VIDEO_IBLOCK_ID'])) {
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'VIDEOS_ELEMENTS', $macros['CONTENT_VIDEO_IBLOCK_ID'], [
                'share_1' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ],
                'share_2' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ],
                'share_3' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ],
                'share_4' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ],
                'share_5' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ],
                'share_6' => [
                    'video_1',
                    'video_2',
                    'video_3'
                ]
            ]);
        }

        /** Привязка фото */
        if (!empty($macros['CONTENT_PHOTO_IBLOCK_ID'])) {
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'PHOTO_ELEMENTS', $macros['CONTENT_PHOTO_IBLOCK_ID'], [
                'share_1' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ],
                'share_2' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ],
                'share_3' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ],
                'share_4' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ],
                'share_5' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ],
                'share_6' => [
                    'photo_1',
                    'photo_2',
                    'photo_3',
                    'photo_4',
                    'photo_5',
                    'photo_6',
                    'photo_7',
                    'photo_8'
                ]
            ]);
        }

        if (!empty($macros['CATALOGS_PRODUCTS_IBLOCK_ID'])) {
            /** Привязка разделов каталога */
            $linkPropertySections($macros['CONTENT_SHARES_IBLOCK_ID'], 'CATALOG_SECTIONS', $macros['CATALOGS_PRODUCTS_IBLOCK_ID'], [
                'share_1' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ],
                'share_2' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ],
                'share_3' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ],
                'share_4' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ],
                'share_5' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ],
                'share_6' => [
                    'automotive',
                    'appliances',
                    'cosmetics',
                    'furniture',
                    'clothing'
                ]
            ]);

            /** Привязка товаров каталога */
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'CATALOG_ELEMENTS', $macros['CATALOGS_PRODUCTS_IBLOCK_ID'], [
                'share_1' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ],
                'share_2' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ],
                'share_3' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ],
                'share_4' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ],
                'share_5' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ],
                'share_6' => [
                    'gps_navigator_digma_alldrive_500',
                    'avtomobilnye_kolonki_6_x9_pioneer_ts_r6951s',
                    'dukhovoy_shkaf_bosch_hbg43t320',
                    'dobrasil_krem_dlya_tela_maracuja_da_bahia',
                    'maslo_dragotsennoe_argany_dlya_litsa_100_ml',
                    'pidzhak_united_colors_of_benetton',
                    'pandora_lace_dress',
                    'dushevaya_kabina_domani_spa_light_88_high_vysokiy_poddon'
                ]
            ]);
        }

        /** Привязка услуг */
        if (!empty($macros['CATALOGS_SERVICES_IBLOCK_ID'])) {
            $linkPropertyElements($macros['CONTENT_SHARES_IBLOCK_ID'], 'SERVICES_ELEMENTS', $macros['CATALOGS_SERVICES_IBLOCK_ID'], [
                'share_1' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ],
                'share_2' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ],
                'share_3' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ],
                'share_4' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ],
                'share_5' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ],
                'share_6' => [
                    'car_repair',
                    'car_wash_service',
                    'realtor_services',
                    'sale_and_rent'
                ]
            ]);
        }
    }

    /** Привязка новостей */
    if (!empty($macros['CONTENT_NEWS_IBLOCK_ID'])) {
        $linkPropertyElements($macros['CONTENT_NEWS_IBLOCK_ID'], 'ASSOCIATED', $macros['CONTENT_NEWS_IBLOCK_ID'], [
            'news_1' => [
                'news_2',
                'news_3',
                'news_4',
                'news_5'
            ],
            'news_2' => [
                'news_3',
                'news_4',
                'news_5',
                'news_6'
            ],
            'news_3' => [
                'news_4',
                'news_5',
                'news_6',
                'news_7'
            ],
            'news_4' => [
                'news_5',
                'news_6',
                'news_7',
                'news_8'
            ],
            'news_5' => [
                'news_6',
                'news_7',
                'news_8',
                'news_1'
            ],
            'news_6' => [
                'news_7',
                'news_8',
                'news_1',
                'news_2'
            ],
            'news_7' => [
                'news_8',
                'news_1',
                'news_2',
                'news_3'
            ],
            'news_8' => [
                'news_1',
                'news_2',
                'news_3',
                'news_4'
            ]
        ]);
    }

    /** Привязка статей */
    if (!empty($macros['CONTENT_ARTICLES_IBLOCK_ID'])) {
        $linkPropertyElements($macros['CONTENT_ARTICLES_IBLOCK_ID'], 'ASSOCIATED', $macros['CONTENT_ARTICLES_IBLOCK_ID'], [
            'article_1' => [
                'article_2',
                'article_3',
                'article_4',
                'article_5'
            ],
            'article_2' => [
                'article_3',
                'article_4',
                'article_5',
                'article_6'
            ],
            'article_3' => [
                'article_4',
                'article_5',
                'article_6',
                'article_7'
            ],
            'article_4' => [
                'article_5',
                'article_6',
                'article_7',
                'article_1'
            ],
            'article_5' => [
                'article_6',
                'article_7',
                'article_1',
                'article_2'
            ],
            'article_6' => [
                'article_7',
                'article_1',
                'article_2',
                'article_3'
            ],
            'article_7' => [
                'article_1',
                'article_2',
                'article_3',
                'article_4'
            ]
        ]);
    }

    /** Привязка блога */
    if (!empty($macros['CONTENT_BLOG_IBLOCK_ID'])) {
        $linkPropertyElements($macros['CONTENT_BLOG_IBLOCK_ID'], 'ASSOCIATED', $macros['CONTENT_BLOG_IBLOCK_ID'], [
            'article_1' => [
                'article_2',
                'article_3',
                'article_4',
                'article_5'
            ],
            'article_2' => [
                'article_3',
                'article_4',
                'article_5',
                'article_6'
            ],
            'article_3' => [
                'article_4',
                'article_5',
                'article_6',
                'article_7'
            ],
            'article_4' => [
                'article_5',
                'article_6',
                'article_7',
                'article_8'
            ],
            'article_5' => [
                'article_6',
                'article_7',
                'article_8',
                'article_1'
            ],
            'article_6' => [
                'article_7',
                'article_8',
                'article_1',
                'article_2'
            ],
            'article_7' => [
                'article_8',
                'article_1',
                'article_2',
                'article_3'
            ],
            'article_8' => [
                'article_1',
                'article_2',
                'article_3',
                'article_4'
            ]
        ]);
    }
}

/** CUSTOM END */

?>
<? include('.end.php') ?>