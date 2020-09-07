<? include(__DIR__.'/.begin.php') ?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\base\Collection;
use intec\core\helpers\StringHelper;
use intec\regionality\models\Region;
use intec\regionality\platform\iblock\properties\RegionProperty;

/**
 * @var Collection $data
 * @var array $languages
 * @var string $solution
 * @var CWizardBase $wizard
 * @var Closure($code, $type, $file, $fields = []) $import
 * @var CWizardStep $this
 */

$code = $solution.'_contacts_'.WIZARD_SITE_ID;
$type = 'content';
$iBlock = $import($code, $type, 'content.contacts');

if (!empty($iBlock)) {
    $macros = $data->get('macros');
    $macros['CONTENT_CONTACTS_IBLOCK_TYPE'] = $type;
    $macros['CONTENT_CONTACTS_IBLOCK_ID'] = $iBlock['ID'];
    $macros['CONTENT_CONTACTS_IBLOCK_CODE'] = $iBlock['CODE'];
    $macros['CONTENT_CONTACTS_CONTACT_ID'] = null;

    $item = CIBlockElement::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $iBlock['ID'],
        'ACTIVE' => 'Y'
    ])->Fetch();

    if (!empty($item))
        $macros['CONTENT_CONTACTS_CONTACT_ID'] = $item['ID'];

    if ($mode == WIZARD_MODE_INSTALL) {
        if (Loader::includeModule('intec.regionality')) {
            $arProperty = CIBlockProperty::GetList(['SORT' => 'ASC'], [
                'IBLOCK_ID' => $iBlock['ID'],
                'CODE' => 'REGIONS'
            ])->Fetch();

            if (empty($arProperty)) {
                $arProperty = (new CIBlockProperty())->Add([
                    'IBLOCK_ID' => $iBlock['ID'],
                    'ACTIVE' => 'Y',
                    'CODE' => 'REGIONS',
                    'NAME' => Loc::getMessage('wizard.services.iblock.import.content.contacts.property.regions'),
                    'PROPERTY_TYPE' => RegionProperty::PROPERTY_TYPE,
                    'USER_TYPE' => RegionProperty::USER_TYPE,
                    'MULTIPLE' => 'Y',
                    'SORT' => '700'
                ]);

                if (!empty($arProperty))
                    $arProperty = CIBlockProperty::GetList(['SORT' => 'ASC'], [
                        'IBLOCK_ID' => $iBlock['ID'],
                        'ID' => $arProperty
                    ])->Fetch();
            }

            if (!empty($arProperty) && $arProperty['PROPERTY_TYPE'] === RegionProperty::PROPERTY_TYPE && $arProperty['USER_TYPE'] === RegionProperty::USER_TYPE) {
                $arRegions = Region::find()->all();
                $rsItems = CIBlockElement::GetList(['SORT' => 'ASC'], [
                    'IBLOCK_ID' => $iBlock['ID'],
                    'ACTIVE' => 'Y'
                ]);

                while ($rsItem = $rsItems->GetNextElement()) {
                    $arItem = $rsItem->GetFields();
                    $arItem['PROPERTIES'] = $rsItem->GetProperties();

                    if (
                        empty($arItem['PROPERTIES']['CITY']) ||
                        empty($arItem['PROPERTIES']['CITY']['VALUE']) ||
                        empty($arItem['PROPERTIES']['REGIONS']) ||
                        !empty($arItem['PROPERTIES']['REGIONS']['VALUE'])
                    ) continue;

                    foreach ($arRegions as $oRegion) {
                        if (StringHelper::toLowerCase($oRegion->name) == StringHelper::toLowerCase($arItem['PROPERTIES']['CITY']['VALUE']))
                            break;

                        $oRegion = null;
                    }

                    if (!empty($oRegion))
                        CIBlockElement::SetPropertyValuesEx($arItem['ID'], $iBlock['ID'], [
                            'REGIONS' => [$oRegion->id]
                        ]);
                }
            }
        }
    }

    $data->set('macros', $macros);
}

?>
<? include(__DIR__.'/.end.php') ?>