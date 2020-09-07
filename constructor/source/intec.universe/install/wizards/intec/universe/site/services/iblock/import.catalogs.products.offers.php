<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use intec\core\base\Collection;
use intec\core\helpers\ArrayHelper;

/**
 * @var Collection $data
 * @var array $languages
 * @var string $solution
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$macros = $data->get('macros');
$code = $solution.'_products_offers_'.WIZARD_SITE_ID;
$type = 'catalogs';
$import = $wizard->GetVar('systemImportIBlocks') === 'Y';
$lite = !Loader::includeModule('catalog') || !ModuleManager::isModuleInstalled('sale');

/** Получаем инфоблок каталога */
$catalogIBlock = ArrayHelper::getValue($macros, 'CATALOGS_PRODUCTS_IBLOCK_ID');
$catalogIBlock = CIBlock::GetByID($catalogIBlock)->Fetch();

/** Если нет инфоблока каталога - выходим */
if (empty($catalogIBlock))
    return;

/** Получаем инфоблок торговых предложений */
$iBlock = CIBlock::GetList([
    'SORT' => 'ASC'
], [
    'XML_ID' => $code,
    'TYPE' => $type
])->Fetch();

/** Если нет инфоблока торговых предложений и разрешен импорт */
if (empty($iBlock) && $import) {
    /** Создаем инфоблок торговых предложений */
    $iBlock = (new CIBlock())->Add([
        'ACTIVE' => 'Y',
        'NAME' => $catalogIBlock['NAME'].' ('.Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.iblock.name').')',
        'SITE_ID' => [WIZARD_SITE_ID],
        'CODE' => $code,
        'XML_ID' => $code,
        'IBLOCK_TYPE_ID' => $type,
        'DETAIL_PAGE_URL' => '#PRODUCT_URL#'
    ]);

    /** Если инфоблок торговых предложений был создан успешно */
    if (!empty($iBlock)) {
        /** Получаем поля инфоблока торговых предложений */
        $iBlock = CIBlock::GetByID($iBlock)->Fetch();

        CIBlock::SetPermission($iBlock['ID'], [
            1 => 'X',
            2 => 'R'
        ]);

        /** Добавляем свойство для хранения доп. изображений */
        (new CIBlockProperty())->Add([
            'IBLOCK_ID' => $iBlock['ID'],
            'ACTIVE' => 'Y',
            'NAME' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.pictures.name'),
            'CODE' => 'PICTURES',
            'PROPERTY_TYPE' => 'F',
            'MULTIPLE' => 'Y'
        ]);

        /** Добавляем свойство торгового предложения "Размер" */
        $sizeProperty = (new CIBlockProperty())->Add([
            'IBLOCK_ID' => $iBlock['ID'],
            'ACTIVE' => 'Y',
            'NAME' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.size.name'),
            'CODE' => 'SIZE',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'L',
            'MULTIPLE' => 'N'
        ]);

        /** Добавляем варианты свойства торгового предложения "Размер" */
        if (!empty($sizeProperty)) {
            (new CIBlockPropertyEnum())->Add([
                'PROPERTY_ID' => $sizeProperty,
                'VALUE' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.size.values.small'),
                'XML_ID' => 'small'
            ]);

            (new CIBlockPropertyEnum())->Add([
                'PROPERTY_ID' => $sizeProperty,
                'VALUE' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.size.values.medium'),
                'XML_ID' => 'medium'
            ]);

            (new CIBlockPropertyEnum())->Add([
                'PROPERTY_ID' => $sizeProperty,
                'VALUE' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.size.values.large'),
                'XML_ID' => 'large'
            ]);
        }
    }
}

/** Если инфоблок торговых предложений существовал или был успешно создан */
if (!empty($iBlock)) {
    $macros['CATALOGS_PRODUCTS_OFFERS_IBLOCK_TYPE'] = $type;
    $macros['CATALOGS_PRODUCTS_OFFERS_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CATALOGS_PRODUCTS_OFFERS_IBLOCK_CODE'] = $iBlock['CODE'];

    /** Если режим не обновление */
    if ($mode !== WIZARD_MODE_UPDATE) {
        /** Если редакция малый бизнес и выше */
        if (!$lite) {
            /** Пробуем получить свойство инфоблока торговых предложений для связки с торговым каталогом */
            $linkProperty = CIBlockProperty::GetList([], [
                'IBLOCK_ID' => $iBlock['ID'],
                'CODE' => 'CML2_LINK'
            ])->Fetch();

            /** Свойство отсутствует - создаем его */
            if (empty($linkProperty)) {
                $linkProperty = (new CIBlockProperty())->Add([
                    'IBLOCK_ID' => $iBlock['ID'],
                    'ACTIVE' => 'Y',
                    'NAME' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.link.name'),
                    'CODE' => 'CML2_LINK',
                    'PROPERTY_TYPE' => 'E',
                    'MULTIPLE' => 'N',
                    'USER_TYPE' => 'SKU',
                    'LINK_IBLOCK_TYPE_ID' => $type,
                    'LINK_IBLOCK_ID' => $catalogIBlock['ID']
                ]);

                if (!empty($linkProperty))
                    $linkProperty = CIBlockProperty::GetByID($linkProperty)->Fetch();
            }

            /** Если свойство существовало или было успешно создано */
            if (!empty($linkProperty)) {
                /** Ищем запись каталога для инфоблока торговых предложений */
                $catalog = CCatalog::GetByID($iBlock['ID']);

                /** Если есть - обновляем связь */
                if (!empty($catalog)) {
                    CCatalog::Update(
                        $iBlock['ID'], [
                            'PRODUCT_IBLOCK_ID' => $catalogIBlock['ID'],
                            'SKU_PROPERTY_ID' => $linkProperty['ID']
                        ]
                    );
                } else { /** Иначе создаем новую запись с актуальными данными */
                    (new CCatalog())->Add([
                        'IBLOCK_ID' => $iBlock['ID'],
                        'PRODUCT_IBLOCK_ID' => $catalogIBlock['ID'],
                        'SKU_PROPERTY_ID' => $linkProperty['ID']
                    ]);
                }
            }
        } else if (Loader::includeModule('intec.startshop')) { /** Если установлен модуль intec.startshop */
            /** Пробуем получить свойство инфоблока торговых предложений для связки с торговым каталогом */
            $linkProperty = CIBlockProperty::GetList([], [
                'IBLOCK_ID' => $iBlock['ID'],
                'CODE' => 'STARTSHOP_LINK'
            ])->Fetch();

            /** Свойство отсутствует - создаем его */
            if (empty($linkProperty)) {
                $linkProperty = (new CIBlockProperty())->Add([
                    'IBLOCK_ID' => $iBlock['ID'],
                    'ACTIVE' => 'Y',
                    'NAME' => Loc::getMessage('wizard.services.iblock.import.catalogs.products.offers.property.link.name'),
                    'CODE' => 'STARTSHOP_LINK',
                    'PROPERTY_TYPE' => 'E',
                    'MULTIPLE' => 'N',
                    'LINK_IBLOCK_TYPE_ID' => $type,
                    'LINK_IBLOCK_ID' => $catalogIBlock['ID']
                ]);

                if (!empty($linkProperty))
                    $linkProperty = CIBlockProperty::GetByID($linkProperty)->Fetch();
            }

            /** Если свойство существовало или было успешно создано */
            if (!empty($linkProperty)) {
                /** Ищем запись каталога для инфоблока торговых предложений */
                $catalog = CStartShopCatalog::GetByIBlock($catalogIBlock['ID'])->Fetch();

                /** Если есть - обновляем связь */
                if (!empty($catalog)) {
                    $properties = [];

                    /** Формируе список свойств торговых предложений */
                    $property = CIBlockProperty::GetList([], [
                        'IBLOCK_ID' => $iBlock['ID'],
                        'CODE' => 'PROPERTY_SIZE'
                    ])->Fetch();

                    if (!empty($property))
                        $properties[] = $property['ID'];

                    CStartShopCatalog::Update($catalog['IBLOCK'], [
                        'OFFERS_IBLOCK' => $iBlock['ID'],
                        'OFFERS_LINK_PROPERTY' => $linkProperty['ID'],
                        'OFFERS_PROPERTIES' => $properties
                    ]);

                    CStartShopToolsIBlock::UpdateProperties($iBlock['ID']);
                }
            }
        }
    }

    $data->set('macros', $macros);
}

?>
<? include(__DIR__.'/.end.php') ?>